<?php

namespace App\Support;

/**
 * Bouwt de schema.org-gegevens (JSON-LD) waarmee Google de winkel,
 * producten, veelgestelde vragen en het kruimelpad begrijpt. De views
 * renderen het resultaat via partials/structured-data.
 */
class StructuredData
{
    /**
     * Website + winkel (fysiek in Zevenaar én online). Hoort op de homepage.
     */
    public static function winkel(): array
    {
        $contact = config('shop.contact');
        $adres = $contact['adres_velden'];

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => url('/').'#website',
                    'url' => url('/'),
                    'name' => config('app.name'),
                    'inLanguage' => 'nl-NL',
                    'publisher' => ['@id' => url('/').'#winkel'],
                ],
                [
                    '@type' => 'Store',
                    '@id' => url('/').'#winkel',
                    'name' => config('app.name'),
                    'description' => 'Webshop en winkel voor professionele nagelproducten van onder meer DNKa\', Valeri en Touch.',
                    'url' => url('/'),
                    'logo' => asset('logo/deluxenailshop_transp_primair_v1.png'),
                    'image' => asset('og-image.jpg'),
                    'email' => config('shop.bedrijf.email'),
                    'telephone' => $contact['telefoon'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $adres['straat'],
                        'postalCode' => $adres['postcode'],
                        'addressLocality' => $adres['plaats'],
                        'addressCountry' => $adres['land'],
                    ],
                    'openingHoursSpecification' => collect($contact['openingsuren'])->map(fn ($uren) => [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => $uren['dagen'],
                        'opens' => $uren['open'],
                        'closes' => $uren['dicht'],
                    ])->all(),
                    'areaServed' => ['NL', 'BE'],
                    'currenciesAccepted' => 'EUR',
                    'paymentAccepted' => 'iDEAL, Bancontact, creditcard, PayPal',
                    'sameAs' => array_values(array_filter([$contact['instagram'] ?? null])),
                ],
            ],
        ];
    }

    /**
     * Kruimelpad. Verwacht [['Naam', 'url'], ...]; het laatste item is de
     * huidige pagina.
     */
    public static function kruimelpad(array $stappen): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($stappen)->values()->map(fn ($stap, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $stap[0],
                'item' => $stap[1],
            ])->all(),
        ];
    }

    /**
     * Product met aanbod, verzending (NL/BE) en retourbeleid. Bewust zonder
     * reviewscore: die tonen we pas zodra er echte klantreviews zijn.
     */
    public static function product(array $product, string $omschrijving, string $categorie): array
    {
        $url = route('product.show', $product['slug']);
        $opVoorraad = ($product['voorraad'] ?? 0) > 0;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['brand'].' '.$product['name'],
            'description' => $omschrijving,
            'image' => collect([$product['image'] ?? null, $product['image_2'] ?? null])->filter()->map(fn ($foto) => asset($foto))->values()->all(),
            'sku' => $product['slug'],
            'brand' => ['@type' => 'Brand', 'name' => $product['brand']],
            'category' => $categorie,
            'url' => $url,
            'offers' => [
                '@type' => 'Offer',
                'url' => $url,
                'price' => number_format($product['price'], 2, '.', ''),
                'priceCurrency' => 'EUR',
                'availability' => $opVoorraad ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => ['@type' => 'Organization', 'name' => config('app.name')],
                'shippingDetails' => collect(config('shop.verzending'))->map(fn ($tarief, $land) => [
                    '@type' => 'OfferShippingDetails',
                    'shippingDestination' => ['@type' => 'DefinedRegion', 'addressCountry' => $land],
                    // Losse productprijs bepaalt of alleen dit artikel al gratis verzonden wordt
                    'shippingRate' => [
                        '@type' => 'MonetaryAmount',
                        'value' => $product['price'] >= $tarief['gratis_vanaf'] ? 0 : $tarief['kosten'],
                        'currency' => 'EUR',
                    ],
                    'deliveryTime' => [
                        '@type' => 'ShippingDeliveryTime',
                        'handlingTime' => ['@type' => 'QuantitativeValue', 'minValue' => 0, 'maxValue' => 1, 'unitCode' => 'DAY'],
                        'transitTime' => ['@type' => 'QuantitativeValue', 'minValue' => 1, 'maxValue' => 2, 'unitCode' => 'DAY'],
                    ],
                ])->values()->all(),
                'hasMerchantReturnPolicy' => [
                    '@type' => 'MerchantReturnPolicy',
                    'applicableCountry' => array_keys(config('shop.verzending')),
                    'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                    'merchantReturnDays' => 14,
                    'returnMethod' => 'https://schema.org/ReturnByMail',
                    'returnFees' => 'https://schema.org/ReturnFeesCustomerResponsibility',
                ],
            ],
        ];
    }

    /**
     * Veelgestelde vragen. Verwacht [['vraag', 'antwoord'], ...].
     */
    public static function faq(array $vragen): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($vragen)->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item[1]],
            ])->all(),
        ];
    }
}

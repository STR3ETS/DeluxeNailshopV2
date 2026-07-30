@extends('layouts.shop')

@section('title', 'Algemene voorwaarden - ' . config('app.name'))
@section('meta_description', 'De algemene voorwaarden van Deluxe Nail Shop: alles over bestellen, betalen, levering, retourneren en garantie.')
@section('meta_keywords', 'algemene voorwaarden, bestellen, levering, retourneren, garantie, Deluxe Nail Shop')

@php
    /*
    |--------------------------------------------------------------------------
    | Algemene voorwaarden
    |--------------------------------------------------------------------------
    | Tekst letterlijk overgenomen uit de aangeleverde algemene voorwaarden (PDF).
    */

    $intro = [
        'Omdat we het bij Deluxe Nail Shop belangrijk vinden.',
    ];

    $articles = [
        [
            'title' => 'Artikel 1: Definities',
            'blocks' => [
                'In deze algemene voorwaarden wordt verstaan:',
                [
                    'opdrachtnemer: Deluxe Nail Shop te Zevenaar.',
                    'opdrachtgever: een wederpartij die een natuurlijk persoon, al dan niet handelt in de uitoefening van een bedrijf of beroep, of een rechtspersoon is.',
                ],
            ],
        ],
        [
            'title' => 'Artikel 2: Toepasselijkheid',
            'blocks' => [
                'De klant gaat bij aankoop van de artikelen en of opleidingen akkoord met de onderstaande voorwaarden, tenzij anders is overeengekomen.',
            ],
        ],
        [
            'title' => 'Artikel 3: Prijzen',
            'blocks' => [
                'Alle prijzen zijn uitgedrukt in euro\'s en zijn exclusief omzetbelasting ( BTW ). Speciale aanbiedingen zijn slechts geldig zolang de voorraad strekt. Bezorgkosten zijn niet bij de prijs inbegrepen. Bezorgkosten voor buiten Nederland hebben speciale tarieven. Ten aanzien van bepaalde betaalmethoden gelden nadere voorwaarden met betrekking tot de bezorgmethode en de daaraan verbonden kosten. Dit wordt duidelijk aan de koper medegedeeld.',
                'Alle prijzen op de website zijn onder voorbehoud van drukfouten c.q. Wijzigingen.',
            ],
        ],
        [
            'title' => 'Artikel 4: Betaling',
            'blocks' => [
                [
                    'iDeal',
                    'Visa',
                    'Paypal',
                    'Bancontact ( BE )',
                    'KBC/CBC Payment Button ( BE )',
                ],
            ],
        ],
        [
            'title' => 'Artikel 5: Levering',
            'blocks' => [
                'Bestellingen worden zo snel mogelijk afgeleverd op het door de opdrachtgever opgegeven adres. Wij streven ernaar om de artikelen binnen 2 dagen te leveren. Genoemde levertijd geldt slechts als indicatie, derhalve kunnen hieraan geen rechten ontleend worden. Bij het foutief invoeren van het adres door de opdrachtgever zal het pakket voor een tweede maal worden aangeboden. De kosten daarvoor zijn voor de rekening van de opdrachtgever. Zodra de te leveren artikelen op het opgegeven afleveradres zijn geleverd, gaat het risico, waar het deze artikelen betreft, over op de opdrachtgever.',
            ],
        ],
        [
            'title' => 'Artikel 6: Annulering',
            'blocks' => [
                'Alle geleverde artikelen dienen in redelijkerwijs mogelijk originele verpakking binnen 14 dagen na de annulering aan "Deluxe Nail Shop" te worden geretourneerd. De opdrachtgever is zelf verantwoordelijk voor de kosten en het risico voor het verzenden. Het aankoopbedrag zal binnen 14 dagen na ontvangst terugbetaald worden aan de opdrachtgever, mits alle artikelen in ongeschonden staat zijn en tevens weer verkoopbaar kunnen worden aangeboden.',
            ],
        ],
        [
            'title' => 'Artikel 7: Retourbeleid',
            'blocks' => [
                'Wil je een product retourneren, dan kan dat binnen 14 dagen na ontvangst zonder reden. Meld je retourzending aan via info@deluxenailshop.nl. Producten dienen binnen 14 dagen retour gestuurd te worden, verzendkosten zijn voor eigen rekening. Na ontvangst van de goederen wordt het verschuldigde bedrag retour gestort. Tijdens deze termijn zal de opdrachtgever zorgvuldig omgaan met de artikelen. Het product kan enkel in mate gebruikt worden voor zover dat nodig is om het product te kunnen beoordelen.',
            ],
        ],
        [
            'title' => 'Artikel 8: Garanties',
            'blocks' => [
                'De deugdelijkheid van geleverde artikelen wordt door "Deluxe Nail Shop" gewaarborgd, met dien verstande dat op geleverde artikelen er niet minder of meer garantie wordt gegeven dan aan opdrachtnemer door leverancier/fabrikant is gegeven. Deze garantie vervalt onmiddellijk indien het gebrek is te wijten aan nalaten verzorging, opzettelijke beschadiging of onoplettendheid.',
                'De opdrachtgever is ermee bekend dat sommige producten allergische reacties kunnen geven bij een beperkt aantal gebruikers.',
            ],
        ],
        [
            'title' => 'Artikel 9: Auteurswet',
            'blocks' => [
                '"Deluxe Nail Shop" behoudt zich de rechten en bevoegdheden voor die haar toekomen op grond van de Auteurswet. Alle rechten inzake verstrekte afbeeldingen blijven eigendom van "Deluxe Nail Shop". De door "Deluxe Nail Shop" te leveren of geleverde producties mogen zonder haar schriftelijke toestemming niet worden gekopieerd, vermenigvuldigd, gereproduceerd of nagemaakt, in welk procedé dan ook, zelfs indien er geen auteursrecht op berust.',
            ],
        ],
        [
            'title' => 'Artikel 10: Aansprakelijkheid',
            'blocks' => [
                'Voor schade, van welke aard ook, ontstaan doordat "Deluxe Nail Shop" is uitgegaan van door de opdrachtgever verstrekte onjuiste en/of onvolledige gegevens, is "Deluxe Nail Shop" niet aansprakelijk, tenzij deze onjuistheid of onvolledigheid voor haar kenbaar behoorde te zijn.',
                'Iedere aansprakelijkheid van "Deluxe Nail Shop" is te allen tijde beperkt tot het bedrag dat in het desbetreffende geval onder de aansprakelijkheidsverzekering van "Deluxe Nail Shop" wordt uitbetaald.',
                '"Deluxe Nail Shop" is niet aansprakelijk indien opdrachtgever de mogelijkheid heeft om inzake de ontstane schade rechtstreeks zijn verzekeringsmaatschappij dan wel die van een derde aan te spreken.',
                '"Deluxe Nail Shop" is nimmer aansprakelijk voor indirecte bedrijfsschade zoals derving van inkomsten en dergelijke door welke oorzaak ook ontstaan.',
            ],
        ],
        [
            'title' => 'Artikel 11: Gebreken',
            'blocks' => [
                '"Deluxe Nail Shop" raad aan om de geleverde artikelen onmiddellijk na ontvangst te controleren en eventuele gebleken gebreken binnen 7 dagen na ontvangst schriftelijk met afbeelding van het gebrek kenbaar te maken. Meld het gebrek aan via info@deluxenailshop.nl.',
            ],
        ],
        [
            'title' => 'Artikel 12: Klacht/Opmerking',
            'blocks' => [
                '"Deluxe Nail Shop" raad aan om de klachten en of opmerking eerst schriftelijk kenbaar te maken door te mailen naar info@deluxenailshop.nl. We zullen samen naar een passende oplossing zoeken.',
            ],
        ],
        [
            'title' => 'Artikel 13: Overmacht',
            'blocks' => [
                'Onder overmacht wordt verstaan alle omstandigheden die de nakoming van de verbintenis verhinderen, en die niet aan "Deluxe Nail Shop" zijn toe te rekenen. Hieronder begrepen weergesteldheid, brand, stakingen, bedrijfsstoringen, stagnatie in toeleveringen om welke reden dan ook, ziekte van onvervangbare werknemers, energiestoringen, sabotage, het op enig moment niet beschikbaar zijn van de internetsite, oorlog, oorlogsdreiging, maatregelingen van overheidswege e.d., zonder dat verplicht is de invloed op de verhindering of vertraging aan te tonen. Tijdens overmacht worden de verplichtingen van opdrachtnemer opgeschort. Indien de periode van overmacht langer duurt dan 1 maand zijn beide partijen bevoegd de overeenkomst te ontbinden zonder dat er in dat geval een verplichting tot schadevergoeding bestaat.',
            ],
        ],
        [
            'title' => 'Artikel 14: Toepasselijk recht',
            'blocks' => [
                'Op elke overeenkomst tussen de opdrachtnemer en de opdrachtgever is het Nederlands recht van toepassing.',
            ],
        ],
    ];
@endphp

@section('content')
    @include('partials.legal-page', [
        'pageName'    => 'Algemene voorwaarden',
        'titlePre'    => 'Algemene ',
        'titleAccent' => 'voorwaarden',
        'intro'       => $intro,
        'articles'    => $articles,
        'metaLine'    => null,
    ])
@endsection

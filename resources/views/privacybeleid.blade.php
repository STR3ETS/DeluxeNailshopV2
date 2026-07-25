@extends('layouts.shop')

@section('title', 'Privacybeleid - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | Privacybeleid
    |--------------------------------------------------------------------------
    | Tekst letterlijk overgenomen uit het aangeleverde privacybeleid (PDF),
    | van toepassing sinds 01-11-2025.
    */

    $intro = [
        'Deluxe Nail Shop respecteert de privacy van de bezoekers van de Website, in het bijzonder de rechten van bezoekers met betrekking tot de geautomatiseerde verwerking van persoonsgegevens. Vanwege volledige transparantie met onze klanten hebben wij daarom een beleid geformuleerd en geïmplementeerd met betrekking tot deze verwerkingen zelf, het doel ervan alsook de mogelijkheden voor betrokkenen om hun de rechten zo goed mogelijk te kunnen uitoefenen.',
        'Totdat u op de website het gebruik van cookies en andere tracking systems accepteert, plaatsen wij geen niet-geanonimiseerde analytische cookies en/of tracking cookies op uw computer, mobiele telefoon of tablet.',
        'Met het voortzetten van het bezoek van de Website accepteert u de volgende gebruikersvoorwaarden.',
    ];

    $articles = [
        [
            'title' => 'Artikel 1 - Wettelijke bepalingen',
            'blocks' => [
                [
                    'Website (hierna ook "de website"): Deluxe nail shop',
                    'Verantwoordelijke voor de verwerking van persoonsgegevens (Hierna ook: "De beheerder"): Deluxe Nail Shop, gevestigd te Lentemorgen 5, 6903 CT Zevenaar, kvk-nummer: 84373466.',
                ],
            ],
        ],
        [
            'title' => 'Artikel 2 - Toegang tot de website',
            'blocks' => [
                'De toegang tot de website en het gebruik is strikt persoonlijk. U zult deze website alsook de gegevens en informatie die daarop verstrekt worden niet gebruiken voor commerciële, politieke of publicitaire doeleinden, dan wel voor enige commerciële aanbiedingen en in het bijzonder niet gebruiken voor ongevraagde elektronische aanbiedingen.',
            ],
        ],
        [
            'title' => 'Artikel 3 - De content van de website',
            'blocks' => [
                'Alle merken, afbeeldingen, teksten, commentaren, illustraties, (animatie)plaatjes, videobeelden, geluiden, alsook alle technische applicaties die gebruikt kunnen worden om de website te laten functioneren en meer in het algemeen alle onderdelen die op deze site zijn gebruikt, zijn beschermd bij wet door intellectuele eigendomsrechten. Iedere reproductie, herhaling, gebruik of aanpassing, op welke wijze dan ook, van het geheel of slechts een onderdeel ervan, met inbegrip van de technische applicaties, zonder voorafgaande schriftelijke toestemming van de verantwoordelijke, is ten strengste verboden. Indien de beheerder niet meteen actie onderneemt tegen enig inbreuk, kan dat niet worden opgevat als stilzwijgende toestemming dan wel het afzien van rechtsvervolging.',
            ],
        ],
        [
            'title' => 'Artikel 4 - Het beheer van de website',
            'blocks' => [
                'Voor het goede beheer van de website kan de beheerder op ieder moment:',
                [
                    'de toegang aan een bepaalde categorie bezoekers schorsen, onderbreken of beperken tot de gehele of een gedeelte van de website',
                    'alle informatie verwijderen die het functioneren van de website kan verstoren of in strijd is met nationale of internationale wetgeving of in strijd is met internet-etiquette',
                    'de website tijdelijk niet beschikbaar hebben teneinde updates uit te kunnen voeren',
                ],
            ],
        ],
        [
            'title' => 'Artikel 5 - Verantwoordelijkheden',
            'blocks' => [
                'De beheerder is in geen geval verantwoordelijk voor falen, storingen, moeilijkheden of onderbrekingen van het functioneren van de website, waardoor de website of een van zijn functionaliteiten niet toegankelijk is. De wijze waarop u verbinding zoekt met de website is uw eigen verantwoordelijkheid. U dient zelf alle geschikte maatregelen te treffen om uw apparatuur en uw gegevens te beschermen tegen onder andere virusaanvallen op het internet. U bent bovendien zelf verantwoordelijk voor de websites en de gegevens die u op internet raadpleegt.',
                'De beheerder is niet aansprakelijk voor juridische procedures die tegen u worden gevoerd:',
                [
                    'vanwege het gebruik van de website of diensten toegankelijk via internet',
                    'vanwege het schenden van de voorwaarden van deze privacy policy',
                ],
                'De beheerder is niet verantwoordelijk voor enige schade die u zelf oploopt, dan wel derden of uw apparatuur oplopen als gevolg van uw verbinding met of het gebruik van de website. U zult zich onthouden van iedere actie tegen de beheerder als gevolg hiervan.',
                'Indien de beheerder betrokken raakt bij een geschil als gevolg van uw gebruik van deze website, is hij gerechtigd alle schade die hij dientengevolge lijdt en nog zal lijden op u te verhalen.',
            ],
        ],
        [
            'title' => 'Artikel 6 - Het verzamelen van gegevens',
            'blocks' => [
                'Uw gegevens worden verzameld door Beauty by Lena. Onder persoonsgegevens worden verstaan: alle informatie over een geïdentificeerde of identificeerbare natuurlijke persoon; als identificeerbaar wordt beschouwd een natuurlijke persoon die direct of indirect kan worden geïdentificeerd, met name aan de hand van een identificator zoals een naam, een identificatienummer, locatiegegevens, een online identificator of een of meer elementen die kenmerkend zijn voor de fysieke, fysiologische, genetische, psychische, economische, culturele of sociale identiteit.',
                'De persoonsgegevens die op de website worden verzameld worden hoofdzakelijk gebruikt door de beheerder voor het onderhouden van relaties met u en indien aan de orde voor het verwerken van uw bestellingen.',
            ],
        ],
        [
            'title' => 'Artikel 7 - Uw rechten met betrekking tot uw gegevens',
            'blocks' => [
                'Op grond van artikel 13 lid 2 sub b AVG heeft eenieder recht op inzage van en rectificatie of wissing van zijn persoonsgegevens of beperking van de hem betreffende verwerking, alsmede het recht tegen de verwerking bezwaar te maken en het recht op gegevensoverdraagbaarheid. U kunt deze rechten uitoefenen door contact met ons op te nemen via info@deluxenailshop.com.',
                'Ieder verzoek daartoe dient te worden vergezeld van een kopie van een geldig identiteitsbewijs, waarop u uw handtekening heeft gezet en onder vermelding van het adres waarop er met u contact kan worden opgenomen. Binnen 1 maand na het ingediende verzoek, krijgt u antwoord op uw verzoek. Afhankelijk van de complexiteit van de verzoeken en het aantal van de verzoeken kan deze termijn indien nodig met 2 maanden worden verlengd.',
            ],
        ],
        [
            'title' => 'Artikel 8 - Verwerking van persoonsgegevens',
            'blocks' => [
                'In geval van schending van enige wet- of regelgeving, waarvan de bezoeker wordt verdacht en waarvoor de autoriteiten persoonsgegevens nodig hebben die de beheerder heeft verzameld, worden deze aan hen verstrekt na een uitdrukkelijk en gemotiveerd verzoek van die autoriteiten, waarna deze persoonsgegevens mitsdien niet meer onder de bescherming van de bepalingen van deze privacyverklaring vallen.',
                'Indien bepaalde informatie noodzakelijk is om toegang te krijgen tot bepaalde functionaliteiten van de website, zal de verantwoordelijke het verplichte karakter van deze informatie aangeven op het moment van het vragen van de gegevens.',
            ],
        ],
        [
            'title' => 'Artikel 9 - Commerciële aanbiedingen',
            'blocks' => [
                'U kunt commerciële aanbiedingen krijgen van de beheerder. Indien u deze niet (meer) wenst te ontvangen, stuurt u een mail naar het volgende adres: info@deluxenailshop.nl.',
                'Indien u tijdens het bezoek van de website enige persoonsgegevens tegenkomt, dient u zich te onthouden van het verzamelen ervan of van enig ander ongeoorloofd gebruik alsook van iedere daad die een inbreuk op de persoonlijke levenssfeer van die perso(o)n(en) oplevert. De beheerder is in geen geval verantwoordelijk in bovengenoemde situaties.',
            ],
        ],
        [
            'title' => 'Artikel 10 - Bewaartermijn gegevens',
            'blocks' => [
                'De door de beheerder van website verzamelde gegevens worden gebruikt en bewaard voor de duur zoals deze bij wet is bepaald.',
            ],
        ],
        [
            'title' => 'Artikel 11 - Cookies',
            'blocks' => [
                'Een cookie is een klein tekstbestand dat bij bezoek aan onze website geplaatst wordt op de harde schijf van uw computer. Een cookie bevat gegevens zodat u bij elk bezoek aan onze website als bezoeker kan worden herkend. Het is dan mogelijk om onze website speciaal op u in te stellen en het inloggen te vergemakkelijken. Wanneer u onze website bezoekt, verschijnt er een banner waarmee we u informeren over het gebruik van cookies. Bij verder gebruik van onze website accepteert u het gebruik ervan. Uw toestemming is geldig voor een periode van dertien maanden.',
                'Wij gebruiken de volgende soorten cookies op onze website:',
                [
                    'Functionele cookies: zoals sessie- en login cookies voor het bijhouden van sessie- en inloginformatie.',
                    'Geanonimiseerde Analytische cookies: om inzage te krijgen in het bezoek aan onze website op basis van informatie over bezoekersaantallen, populaire pagina\'s en onderwerpen. Op deze wijze kunnen we de communicatie en informatievoorziening beter afstemmen op de behoeften van bezoekers van onze website. We kunnen niet zien wie onze websites bezoekt of vanaf welke pc het bezoek plaatsvindt.',
                    'Niet-geanonimiseerde Analytische cookies: om inzage te krijgen in het bezoek aan onze website op basis van informatie over bezoekersaantallen, populaire pagina\'s en onderwerpen. Op deze wijze kunnen we de communicatie en informatievoorziening beter afstemmen op de behoeften van bezoekers van onze website.',
                    'Tracking cookies: zoals advertentiecookies die zijn bedoeld voor het tonen van relevante advertenties. Uit de informatie over bezochte websites kunnen persoonlijke interesses worden afgeleid. Daarmee kunnen organisaties hun websitebezoekers bijvoorbeeld gerichte advertenties tonen. Tracking cookies maken het mogelijk om profielen van mensen op te stellen en hen anders te behandelen. Met tracking cookies worden doorgaans persoonsgegevens verwerkt.',
                ],
                'Meer specifiek gebruiken wij de volgende cookies:',
                [
                    'Google Analytics geanonimiseerd (analytische cookies)',
                    'Google Analytics (analytische cookie)',
                    'Facebook (tracking cookie)',
                    'Google Adwords (tracking cookie)',
                ],
                'Wanneer u onze website bezoekt kunnen cookies afkomstig van de verantwoordelijke en/of derden op uw apparatuur worden geïnstalleerd.',
                'Voor meer informatie over het gebruik, het beheer en het verwijderen van cookies voor elk besturingstype, nodigen wij u uit om de volgende link te raadplegen: https://autoriteitpersoonsgegevens.nl/nl/onderwerpen/internet-telefoon-tv-en-post/cookies#faq',
            ],
        ],
        [
            'title' => 'Artikel 12 - Beeldmateriaal en aangeboden producten',
            'blocks' => [
                'Aan het beeldmateriaal dat behoort bij de aangeboden producten op de website kunnen geen rechten worden ontleend.',
            ],
        ],
        [
            'title' => 'Artikel 13 - Toepasselijk recht',
            'blocks' => [
                'Op deze voorwaarden is Nederlands Recht van toepassing. De rechtbank van de vestigingsplaats van de beheerder is exclusief bevoegd bij eventuele geschillen omtrent deze voorwaarden, behoudens wanneer hierop een wettelijke uitzondering van toepassing is.',
            ],
        ],
        [
            'title' => 'Artikel 14 - Contact',
            'blocks' => [
                'Voor vragen, productinformatie of informatie over de website zelf, kunt u zich richten tot: Deluxe Nail Shop, info@deluxenailshop.nl',
            ],
        ],
    ];
@endphp

@section('content')
    @include('partials.legal-page', [
        'pageName'    => 'Privacybeleid',
        'titlePre'    => 'Privacy',
        'titleAccent' => 'beleid',
        'intro'       => $intro,
        'articles'    => $articles,
        'metaLine'    => 'Deze privacyverklaring is van toepassing sinds 01-11-2025 tot nader order.',
    ])
@endsection

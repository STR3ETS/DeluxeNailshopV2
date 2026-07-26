<?php

/*
|--------------------------------------------------------------------------
| Producten (demo-data)
|--------------------------------------------------------------------------
|
| Gedeelde bron voor de productenpagina, de detailpagina en de bestsellers.
| Later te vervangen door producten uit de database - houd de veldnamen aan.
|
| Detailvelden (optioneel per product; ontbreken ze, dan gebruikt de
| detailpagina nette standaardteksten):
|   description, kenmerken ([titel, tekst]), gebruiksaanwijzing (stappen),
|   inhoud, voorzorg, bewaren.
| 'image' en 'bg' zijn ook optioneel - zonder deze velden krijgt het product
| een roulerende tijdelijke foto + achtergrondtint.
|
*/

return [

    // ── Base & Top ──────────────────────────────────────────────────────
    [
        'brand' => 'DNKa\'', 'name' => 'Fiber Base - 12ml', 'reviews' => 154, 'price' => 12.49, 'old_price' => null,
        'badge' => 'Nieuw', 'badge_gold' => false, 'category' => 'base-top', 'subcategory' => 'rubber-base',
        'description' => 'De DNKa\' Fiber Base is een transparante, flexibele en sterke base gel, speciaal ontwikkeld voor dunne, broze en gespleten nagels. Dankzij de vernieuwde formule met zijden vezels biedt deze base extra stevigheid en langdurige bescherming tegen scheuren en splijten.',
        'kenmerken' => [
            ['HEMA- en TPO-vrij', 'zacht voor de nagelplaat, geschikt voor gevoelige huid.'],
            ['Versterkend', 'ideaal voor beschadigde, broze of gespleten nagels.'],
            ['Zelfstandig product', 'geen extra basislaag nodig.'],
            ['Comfortabel in gebruik', 'gladde, middel-dikke textuur met vezels voor optimale houvast.'],
            ['Bescherming tegen chips en splijten', 'verlengt de levensduur van nagellak of gel.'],
        ],
        'gebruiksaanwijzing' => [
            'Bereid de nagel voor: reinig en vijl de nagelplaat.',
            'Reinig met Valeri / DNKa\' Nail Prep & Cleanser 3in1.',
            'Breng Valeri / DNKa\' Dehydrator en Ultrabond aan voor optimale hechting.',
            'Breng een dunne laag DNKa\' Fiber Base aan (uitharden: 60 sec in LED/Hybrid of 90 sec in UV).',
            'Werk af met jouw favoriete Valeri BIAB, gel, acrylgel of gelpolish.',
            'Werk af met een Valeri / DNKa\' Top Coat en hard uit (120 sec in LED/hybridelamp of 180 sec in UV-lamp voor extra duurzaamheid).',
        ],
        'inhoud' => '12 ml',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Rubber Base - Clear 15ml', 'reviews' => 311, 'price' => 12.95, 'old_price' => null,
        'badge' => 'Bestseller', 'badge_gold' => true, 'category' => 'base-top', 'subcategory' => 'rubber-base',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Rubber Base - Milky White 15ml', 'reviews' => 187, 'price' => 12.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'base-top', 'subcategory' => 'rubber-base',
    ],
    [
        'brand' => 'Valeri', 'name' => 'No-Wipe Top Coat - High Gloss', 'reviews' => 258, 'price' => 11.50, 'old_price' => null,
        'badge' => 'Top rated', 'badge_gold' => true, 'category' => 'base-top', 'subcategory' => 'top-coat',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Cover Top - Blush', 'reviews' => 74, 'price' => 11.95, 'old_price' => 13.95,
        'badge' => null, 'badge_gold' => false, 'category' => 'base-top', 'subcategory' => 'cover-top',
    ],

    // ── Cover Base ──────────────────────────────────────────────────────
    [
        'brand' => 'DNKa\'', 'name' => 'Cover Base - Nude Peach 15ml', 'reviews' => 142, 'price' => 13.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'cover-base', 'subcategory' => '',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Cover Base - Soft Rose 15ml', 'reviews' => 98, 'price' => 13.95, 'old_price' => null,
        'badge' => 'Nieuw', 'badge_gold' => false, 'category' => 'cover-base', 'subcategory' => '',
    ],

    // ── Gellak ──────────────────────────────────────────────────────────
    [
        'brand' => 'DNKa\'', 'name' => 'Gellak Color - Terracotta №014', 'reviews' => 203, 'price' => 9.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'gellak', 'subcategory' => 'gellak-color',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Gellak Color - Mauve №221', 'reviews' => 156, 'price' => 9.95, 'old_price' => 11.95,
        'badge' => null, 'badge_gold' => false, 'category' => 'gellak', 'subcategory' => 'gellak-color',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Cat\'s Eye Gelpolish - Oranje №0001', 'reviews' => 482, 'price' => 9.95, 'old_price' => 12.95,
        'badge' => 'Bestseller', 'badge_gold' => true, 'category' => 'gellak', 'subcategory' => 'gellak-cat-eye',
        'bg' => ['#fdeadd', '#f9ccae'], 'image' => 'temp-producten/f4WT5upVonPx7Ay5Oy70YA6RRAXfRUSV5GUfW6OQ-removebg-preview.png',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Cat\'s Eye Gelpolish - Koraalrood №0002', 'reviews' => 96, 'price' => 9.95, 'old_price' => null,
        'badge' => 'Nieuw', 'badge_gold' => false, 'category' => 'gellak', 'subcategory' => 'gellak-cat-eye',
        'bg' => ['#fde4dc', '#f8bfae'], 'image' => 'temp-producten/XUsu7Rf4mzqONGKUTXXDfF96fIrjgT1x6FSX79ir-removebg-preview.png',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Cat\'s Eye Gelpolish - Rood №0003', 'reviews' => 311, 'price' => 9.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'gellak', 'subcategory' => 'gellak-cat-eye',
        'bg' => ['#fce3e6', '#f6bec7'], 'image' => 'temp-producten/9xn8lsHC0aiUZIb5MYlVgqmIiTSdrIUNflduKe2i-removebg-preview.png',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Cat\'s Eye Gelpolish - Fuchsia №0005', 'reviews' => 258, 'price' => 9.95, 'old_price' => null,
        'badge' => 'Top rated', 'badge_gold' => true, 'category' => 'gellak', 'subcategory' => 'gellak-cat-eye',
        'bg' => ['#fbe2ef', '#f2bbd9'], 'image' => 'temp-producten/PxxTPzW8IR2zK7bsjomwEewlgY8IvXxRTIg19CFl-removebg-preview.png',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Gellak Flash - Disco Pink', 'reviews' => 67, 'price' => 10.95, 'old_price' => null,
        'badge' => 'Nieuw', 'badge_gold' => false, 'category' => 'gellak', 'subcategory' => 'gellak-flash',
    ],

    // ── Gel & Acrygel ───────────────────────────────────────────────────
    [
        'brand' => 'DNKa\'', 'name' => 'Acrygel - Cover Pink 30ml', 'reviews' => 312, 'price' => 19.95, 'old_price' => null,
        'badge' => 'Bestseller', 'badge_gold' => true, 'category' => 'gel-acrygel', 'subcategory' => 'acrygel-polygel',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Polygel - Clear 30ml', 'reviews' => 145, 'price' => 18.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'gel-acrygel', 'subcategory' => 'acrygel-polygel',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Builder Gel - Milky Rose 15ml', 'reviews' => 482, 'price' => 17.95, 'old_price' => 21.95,
        'badge' => null, 'badge_gold' => false, 'category' => 'gel-acrygel', 'subcategory' => 'builder-gel',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Jelly Gelly - Peach 15ml', 'reviews' => 89, 'price' => 16.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'gel-acrygel', 'subcategory' => 'jelly-gelly',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Fast Gel - Blossom 15ml', 'reviews' => 121, 'price' => 15.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'gel-acrygel', 'subcategory' => 'fast-gel',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Mousse Gel - Cotton White 30ml', 'reviews' => 54, 'price' => 16.95, 'old_price' => null,
        'badge' => 'Nieuw', 'badge_gold' => false, 'category' => 'gel-acrygel', 'subcategory' => 'mousse-gel',
    ],

    // ── Nail Art ────────────────────────────────────────────────────────
    [
        'brand' => 'Valeri', 'name' => 'Ombre Spray - Rose Gold', 'reviews' => 176, 'price' => 8.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'nail-art', 'subcategory' => 'ombre-spray',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Metali Gel - Chrome Silver', 'reviews' => 93, 'price' => 7.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'nail-art', 'subcategory' => 'metali-gel',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Paint Gel - Zwart 5ml', 'reviews' => 210, 'price' => 7.95, 'old_price' => null,
        'badge' => 'Bestseller', 'badge_gold' => true, 'category' => 'nail-art', 'subcategory' => 'paint-gel',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Chrome Gel - Aurora', 'reviews' => 148, 'price' => 8.95, 'old_price' => 10.95,
        'badge' => null, 'badge_gold' => false, 'category' => 'nail-art', 'subcategory' => 'chrome-gel',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Gypsum - Sugar Effect', 'reviews' => 41, 'price' => 9.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'nail-art', 'subcategory' => 'gypsum',
    ],

    // ── Liquids ─────────────────────────────────────────────────────────
    [
        'brand' => 'DNKa\'', 'name' => 'Prep - Dehydrator 15ml', 'reviews' => 265, 'price' => 7.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'liquids', 'subcategory' => 'prep',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Cleanser - 500ml', 'reviews' => 189, 'price' => 9.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'liquids', 'subcategory' => 'cleansers-removers',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Cuticle Remover - 15ml', 'reviews' => 77, 'price' => 6.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'liquids', 'subcategory' => 'cuticle-remover',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Nagelriem Olie - Amandel 15ml', 'reviews' => 134, 'price' => 6.95, 'old_price' => 8.95,
        'badge' => null, 'badge_gold' => false, 'category' => 'liquids', 'subcategory' => 'nagelriem-olie',
    ],

    // ── Benodigdheden ───────────────────────────────────────────────────
    [
        'brand' => 'DNKa\'', 'name' => 'Penseel - Ovaal #6', 'reviews' => 156, 'price' => 12.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'benodigdheden', 'subcategory' => 'penselen-tools',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Top Nail Forms - 120 stuks', 'reviews' => 88, 'price' => 9.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'benodigdheden', 'subcategory' => 'top-nail-forms',
    ],
    [
        'brand' => 'DNKa\'', 'name' => 'Gel Tips - Almond Medium 550 stuks', 'reviews' => 201, 'price' => 14.95, 'old_price' => null,
        'badge' => 'Top rated', 'badge_gold' => true, 'category' => 'benodigdheden', 'subcategory' => 'gel-tips',
    ],
    [
        'brand' => 'Valeri', 'name' => 'Nail Art Tool Set - 5-delig', 'reviews' => 63, 'price' => 11.95, 'old_price' => null,
        'badge' => null, 'badge_gold' => false, 'category' => 'benodigdheden', 'subcategory' => 'diversen',
    ],

];

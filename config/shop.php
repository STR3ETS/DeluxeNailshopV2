<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Categorieën
    |--------------------------------------------------------------------------
    |
    | De volledige categorieboom van de shop. Wordt gebruikt voor de
    | header-navigatie (die doorlinkt naar /producten?categorie=slug),
    | de categoriekaarten op de homepage en de filters op de productenpagina.
    | 'dab' = [kleur van, kleur naar] voor het kleurklodder-icoon op de kaarten.
    |
    */

    'categories' => [
        [
            'name' => 'Base & Top',
            'slug' => 'base-top',
            'sub'  => 'Rubber base & top coats',
            'dab'  => ['#f6e3de', '#e9c9c2'],
            'subcategories' => [
                ['name' => 'Rubber base', 'slug' => 'rubber-base'],
                ['name' => 'Top coat',    'slug' => 'top-coat'],
                ['name' => 'Cover top',   'slug' => 'cover-top'],
            ],
        ],
        [
            'name' => 'Cover Base',
            'slug' => 'cover-base',
            'sub'  => 'Camouflage & cover',
            'dab'  => ['#f3e7db', '#d8bb98'],
            'subcategories' => [],
        ],
        [
            'name' => 'Gellak',
            'slug' => 'gellak',
            'sub'  => 'Color, cat eye & flash',
            'dab'  => ['#eec4bb', '#d99a90'],
            'subcategories' => [
                ['name' => 'Gellak color',   'slug' => 'gellak-color'],
                ['name' => 'Gellak cat eye', 'slug' => 'gellak-cat-eye'],
                ['name' => 'Gellak flash',   'slug' => 'gellak-flash'],
            ],
        ],
        [
            'name' => 'Gel & Acrygel',
            'slug' => 'gel-acrygel',
            'sub'  => 'Builder, polygel & meer',
            'dab'  => ['#dcb99e', '#b38867'],
            'subcategories' => [
                ['name' => 'Acrygel / polygel', 'slug' => 'acrygel-polygel'],
                ['name' => 'Builder gel',       'slug' => 'builder-gel'],
                ['name' => 'Jelly gelly',       'slug' => 'jelly-gelly'],
                ['name' => 'Fast gel',          'slug' => 'fast-gel'],
                ['name' => 'Mousse gel',        'slug' => 'mousse-gel'],
            ],
        ],
        [
            'name' => 'Nail Art',
            'slug' => 'nail-art',
            'sub'  => 'Chrome, paint & ombre',
            'dab'  => ['#e6cfd8', '#c9a3b4'],
            'subcategories' => [
                ['name' => 'Ombre spray', 'slug' => 'ombre-spray'],
                ['name' => 'Metali gel',  'slug' => 'metali-gel'],
                ['name' => 'Paint gel',   'slug' => 'paint-gel'],
                ['name' => 'Chrome gel',  'slug' => 'chrome-gel'],
                ['name' => 'Gypsum',      'slug' => 'gypsum'],
            ],
        ],
        [
            'name' => 'Liquids',
            'slug' => 'liquids',
            'sub'  => 'Prep, cleansers & olie',
            'dab'  => ['#f0e6dc', '#cfc0b0'],
            'subcategories' => [
                ['name' => 'Prep',                 'slug' => 'prep'],
                ['name' => 'Cleansers / removers', 'slug' => 'cleansers-removers'],
                ['name' => 'Cuticle remover',      'slug' => 'cuticle-remover'],
                ['name' => 'Nagelriem olie',       'slug' => 'nagelriem-olie'],
            ],
        ],
        [
            'name' => 'Benodigdheden',
            'slug' => 'benodigdheden',
            'sub'  => 'Penselen, tips & tools',
            'dab'  => ['#d9cfc5', '#a89685'],
            'subcategories' => [
                ['name' => 'Penselen / tools', 'slug' => 'penselen-tools'],
                ['name' => 'Top nail forms',   'slug' => 'top-nail-forms'],
                ['name' => 'Gel tips',         'slug' => 'gel-tips'],
                ['name' => 'Diversen',         'slug' => 'diversen'],
            ],
        ],
    ],

    'brands' => ['DNKa', 'Valeri'],

    // Drempel voor gratis verzending binnen Nederland (in euro's) - gebruikt
    // in de announcement-balk en de winkelwagen-dropdown.
    'free_shipping_from' => 75,

    // Verzendtarieven per land (conform deluxenailshop.nl, verzending via PostNL).
    'verzending' => [
        'NL' => ['kosten' => 7.45,  'gratis_vanaf' => 75],
        'BE' => ['kosten' => 12.35, 'gratis_vanaf' => 100],
    ],

    // Tijdelijke productfoto's + zachte achtergrondtinten; rouleren over
    // producten zonder eigen 'image'/'bg'-veld.
    'temp_images' => [
        'temp-producten/f4WT5upVonPx7Ay5Oy70YA6RRAXfRUSV5GUfW6OQ-removebg-preview.png',
        'temp-producten/XUsu7Rf4mzqONGKUTXXDfF96fIrjgT1x6FSX79ir-removebg-preview.png',
        'temp-producten/9xn8lsHC0aiUZIb5MYlVgqmIiTSdrIUNflduKe2i-removebg-preview.png',
        'temp-producten/PxxTPzW8IR2zK7bsjomwEewlgY8IvXxRTIg19CFl-removebg-preview.png',
    ],
    'card_tints' => [
        ['#f6e3de', '#ecc9bf'],
        ['#f3e7db', '#e0c5a8'],
        ['#efe4ea', '#dcc2ce'],
        ['#eadfd2', '#cdb79c'],
        ['#fde4dc', '#f8bfae'],
        ['#fbe2ef', '#f2bbd9'],
    ],

];

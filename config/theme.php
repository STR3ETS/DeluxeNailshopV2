<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kleuren
    |--------------------------------------------------------------------------
    |
    | Het volledige kleurenschema van de site. Elke key wordt in de views
    | als CSS-variabele beschikbaar gemaakt (bijv. 'primary' => var(--primary)).
    | Pas hier de kleuren aan en de hele site kleurt mee — ook de tinten en
    | schaduwen, die via color-mix() van deze basiskleuren worden afgeleid.
    |
    */

    'colors' => [
        'primary'      => '#b38867', // caramel — hoofdkleur (knoppen, accenten, links)
        'primary-deep' => '#9a7052', // donkere variant voor hover-states
        'dark'         => '#3d2b21', // espresso — tekst, footer, donkere vlakken
        'dark-soft'    => '#5a443a', // zachtere tekstkleur (subtitels, bijschriften)
        'bg'           => '#faf4ee', // cream — paginakleur
        'bg-deep'      => '#f3e7db', // iets diepere achtergrond (secties)
        'accent'       => '#e9c9c2', // blush — decoratieve accenten
        'accent-soft'  => '#f6e3de', // lichte blush (iconen, panelen)
        'gold'         => '#d8bb98', // goud — highlights in donkere vlakken
        'white'        => '#fffdfb', // warm wit (kaarten)
    ],

    /*
    |--------------------------------------------------------------------------
    | Typografie
    |--------------------------------------------------------------------------
    */

    'fonts' => [
        'serif'  => '"Fraunces", Georgia, serif',
        'sans'   => '"Outfit", "Segoe UI", sans-serif',
        'google' => 'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..700;1,9..144,300..700&family=Outfit:wght@300;400;500;600;700&display=swap',
    ],

    /*
    |--------------------------------------------------------------------------
    | Vormgeving
    |--------------------------------------------------------------------------
    */

    'radius' => '22px', // basis border-radius voor kaarten en panelen

];

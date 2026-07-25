<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Beheer-modules
    |--------------------------------------------------------------------------
    | Gebruikt voor de pill-navigatie in de admin-layout én de module-tegels
    | op het dashboard.
    */

    'modules' => [
        ['label' => 'Producten',    'route' => 'admin.producten',    'icon' => 'fa-bottle-droplet', 'sub' => 'Assortiment & prijzen'],
        ['label' => 'Bestellingen', 'route' => 'admin.bestellingen', 'icon' => 'fa-box-open',       'sub' => 'Orders & statussen'],
        ['label' => 'Voorraad',     'route' => 'admin.voorraad',     'icon' => 'fa-boxes-stacked',  'sub' => 'Aantallen & meldingen'],
        ['label' => 'Facturen',     'route' => 'admin.facturen',     'icon' => 'fa-file-invoice',   'sub' => 'Facturen & omzet'],
        ['label' => 'Instellingen', 'route' => 'admin.instellingen', 'icon' => 'fa-gear',           'sub' => 'Shop-instellingen'],
    ],

];

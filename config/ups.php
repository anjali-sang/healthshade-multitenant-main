<?php

return [
    /*
    |--------------------------------------------------------------------------
    | UPS API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for UPS shipping API integration
    |
    */

    'access_key' => env('UPS_ACCESS_KEY', ''),
    'username' => env('UPS_USERNAME', ''),
    'password' => env('UPS_PASSWORD', ''),
    'account_number' => env('UPS_ACCOUNT_NUMBER', ''),
    'production' => env('UPS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Default Shipping Options
    |--------------------------------------------------------------------------
    */

    'default_service' => '03', // UPS Ground
    'package_type' => '02', // Customer Supplied Package
    'weight_unit' => 'LBS',
    'dimension_unit' => 'IN',
    'currency' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Service Codes
    |--------------------------------------------------------------------------
    */

    'services' => [
        '01' => 'UPS Next Day Air',
        '02' => 'UPS 2nd Day Air',
        '03' => 'UPS Ground',
        '07' => 'UPS Worldwide Express',
        '08' => 'UPS Worldwide Expedited',
        '11' => 'UPS Standard',
        '12' => 'UPS 3 Day Select',
        '13' => 'UPS Next Day Air Saver',
        '14' => 'UPS Next Day Air Early A.M.',
        '54' => 'UPS Worldwide Express Plus',
        '59' => 'UPS 2nd Day Air A.M.',
        '65' => 'UPS Saver'
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Types
    |--------------------------------------------------------------------------
    */

    'package_types' => [
        '01' => 'UPS Letter',
        '02' => 'Customer Supplied Package',
        '03' => 'Tube',
        '04' => 'PAK',
        '21' => 'UPS Express Box',
        '24' => 'UPS 25KG Box',
        '25' => 'UPS 10KG Box',
        '30' => 'Pallet',
        '2a' => 'Small Express Box',
        '2b' => 'Medium Express Box',
        '2c' => 'Large Express Box'
    ]
];
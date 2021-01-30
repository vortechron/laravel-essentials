<?php

return [

    'twilio' => [
        'AUTHY_API_KEY'  => env('AUTHY_API_KEY'),
        'TWILIO_ACCOUNT_SID'  => env('TWILIO_ACCOUNT_SID'),
        'TWILIO_AUTH_TOKEN'  => env('TWILIO_AUTH_TOKEN'),
        'TWILIO_PHONE'  => env('TWILIO_PHONE'),
     ],

    'enable_blade_include' => false,

    'enable_blade_components' => false,
    
    'enable_error' => false,

    'view_namespace' => 'vtr',
    
    'admin' => [
        'view_path' => 'admin',

        'route_prefix' => 'admin'
    ],

    'meta' => [
        /*
        |--------------------------------------------------------------------------
        | Site default title
        |--------------------------------------------------------------------------
        |
        */

        'title' => 'My Site',

        /*
        |--------------------------------------------------------------------------
        | Limit title meta tag length
        |--------------------------------------------------------------------------
        |
        | To best SEO implementation, limit tags.
        |
        */

        'title_limit' => 70,

        /*
        |--------------------------------------------------------------------------
        | Limit description meta tag length
        |--------------------------------------------------------------------------
        |
        | To best SEO implementation, limit tags.
        |
        */

        'description_limit' => 200,

        /*
        |--------------------------------------------------------------------------
        | OpenGraph values
        |--------------------------------------------------------------------------
        |
        */

        'open_graph' => [
            'site_name' => 'My Site',
            'type' => 'website'
        ],

        /*
        |--------------------------------------------------------------------------
        | Twitter Card values
        |--------------------------------------------------------------------------
        |
        */

        'twitter' => [
            'card' => 'summary',
            'creator' => '@mysite',
            'site' => '@mysite'
        ],

        /*
        |--------------------------------------------------------------------------
        | Supported languages
        |--------------------------------------------------------------------------
        |
        */

        'locale_url' => '[scheme]://[locale][host][uri]',

        /*
        |--------------------------------------------------------------------------
        | Supported languages
        |--------------------------------------------------------------------------
        |
        */

        'locales' => ['en', 'es'],

    ]
];
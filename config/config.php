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
    ]
];
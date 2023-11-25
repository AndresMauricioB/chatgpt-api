<?php

return [
    'cliente_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),

    'settings' => [
        'mode' => 'sandbox',
        'http.ConnectionTimeOut' => 30, // Corrección de la clave
        'log.LogEnabled' => true, // Corrección de la clave
        'log.FileName' => storage_path('/logs/paypal.log'),
        'log.LogLevel' => 'ERROR'
    ]
];



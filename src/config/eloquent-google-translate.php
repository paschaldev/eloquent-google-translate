<?php 

return [

    'database_table' => 'translations',

    'google_api_key' => env('GOOGLE_API_KEY'),

    'models' => [

        
    ],

    'queueable' => true,

    /*
    |--------------------------------------------------------------------------
    | Translation Languages
    |--------------------------------------------------------------------------
    |
    | A list of languages to translate to.
    
    | Check here for valid values https://cloud.google.com/translate/docs/languages
    |
    | @required Array
    */
    'languages' => [

        'fr',
        'es',
        'pt',
        'sw',
        'ar',
        'yo',
        'ha',
        'ig'
    ]
];
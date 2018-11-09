<?php 

return [

    /*
    |--------------------------------------------------------------------------
    | Database Table name
    |--------------------------------------------------------------------------
    |
    | Database table where translations are stored
    |
    | @required String
    */

    'database_table' => 'translations',

    /*
    |--------------------------------------------------------------------------
    | Google API Key
    |--------------------------------------------------------------------------
    |
    | Google API Key, you can get yours from Google Cloud Console and make sure 
    | you have translations enabled.
    |
    | @required String
    */

    'google_api_key' => env('GOOGLE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Queue Translations
    |--------------------------------------------------------------------------
    |
    | This library uses Google API to translate your columns. It is very 
    | important this valus is set to true so it doesn't slow down your app. 
    |
    | It uses Laravel's internal queue system so be sure you understand how 
    | queue works in Laravel and you have already setup your queue.
    |
    | @required Boolean
    */
    'queue' => false,

    /*
    |--------------------------------------------------------------------------
    | Translation Locales
    |--------------------------------------------------------------------------
    |
    | A list of locales to translate to.
    |
    | Check here for valid values https://cloud.google.com/translate/docs/languages
    |
    | @required Array
    */
    'locales' => [

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
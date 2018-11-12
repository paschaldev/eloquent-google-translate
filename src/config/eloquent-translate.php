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
    | Queue Name
    |--------------------------------------------------------------------------
    |
    | The name of the queue where translations should be stored. Setting this 
    | value to null will use the `default` laravel queue
    |
    | @var String|null
    */
    'queue_name' => null,

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
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | Default Locale to use if none is set. 
    |
    | If it is set to a value not in your locales array, the translation will 
    | default to the original value set in your model.
    |
    | @var String
    */
    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Automatically translate 
    |--------------------------------------------------------------------------
    |
    | Attempt to translate locales automatically from the locale set in the user 
    | browser
    |
    | @var Boolean
    */
    'auto_translate' => true
];
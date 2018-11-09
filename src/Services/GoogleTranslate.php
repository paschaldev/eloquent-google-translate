<?php 

namespace PaschalDev\EloquentTranslate\Services;

use PaschalDev\EloquentTranslate\Contracts\TranslatorContract;

class GoogleTranslate implements TranslatorContract {

    public function translate($text, $locale)
    {
        $translatedText = null;

        try 
        {
            $client = new \GoogleTranslate\Client( config('eloquent-translate.google_api_key') );

            $translatedText = $client->translate($text, $locale);
        }
        catch(\Exeption $e){ }

        return $translatedText;
    }
}
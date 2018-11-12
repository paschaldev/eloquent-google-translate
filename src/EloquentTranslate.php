<?php 

namespace PaschalDev\EloquentTranslate;

class EloquentTranslate {

    private $locale;

    public function __construct( $locale = null )
    {
        $this->locale = $locale;
    }

    /**
     * Gets the table name where translations are stored
     *
     * @return string
     */
    public function getTranslationsTableName()
    {    
        return config('eloquent-translate.database_table');
    }

    public function getLocale()
    {
        return $this->locale ?? config('eloquent-translate.fallback_locale');
    }
}
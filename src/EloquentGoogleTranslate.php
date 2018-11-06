<?php 

namespace PaschalDev\EloquentGoogleTranslate;

class EloquentGoogleTranslate {

    /**
     * Gets the table name where translations are stored
     *
     * @return string
     */
    public function getTranslationsTableName()
    {    
        return config('eloquent-google-translate.database_table');
    }
}
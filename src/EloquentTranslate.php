<?php 

namespace PaschalDev\EloquentTranslate;

class EloquentTranslate {

    private $translatorService;

    public function __construct(GoogleTranslate $translatorService)
    {
        $this->translatorService = $translatorService;
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

    
}
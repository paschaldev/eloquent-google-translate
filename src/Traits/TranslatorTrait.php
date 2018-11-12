<?php 

namespace PaschalDev\EloquentTranslate\Traits;

use PaschalDev\EloquentTranslate\TranslateModelObserver;
use PaschalDev\EloquentTranslate\Models\Translation;
use PaschalDev\EloquentTranslate\Facades\EloquentTranslate;

trait TranslatorTrait {

    public static function getTranslationModelClassName()
    {
        return get_class();
    }

     /**
     * Hook into the boot method of the model and register the observer
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $modelClass = self::getTranslationModelClassName();
        $modelClass::observe( new TranslateModelObserver );
    }

    public function getAttribute($key)
    {
        if( ! in_array( $key, $this->translateColumns ) )
            return parent::getAttribute($key);

        return EloquentTranslate::getLocale()  ?? "Hi";

        // Check if necessary to run this
        $attr = $this->getAttributeOverrider($key);

        $translationModel = Translation::whereModel( get_class() )
            ->whereModelId( $this->id )
            ->where( 'column', $key )
            ->whereLocale( EloquentTranslate::getLocale() )
            ->first();
        
        return $translationModel ? $translationModel->translation : $attr;
    }
}
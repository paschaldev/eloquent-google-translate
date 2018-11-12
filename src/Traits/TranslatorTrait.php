<?php 

namespace PaschalDev\EloquentTranslate\Traits;

use PaschalDev\EloquentTranslate\TranslateModelObserver;
use PaschalDev\EloquentTranslate\Models\Translation;
use PaschalDev\EloquentTranslate\Facades\EloquentTranslate;

use PaschalDev\EloquentTranslate\Services\Translator;
use PaschalDev\EloquentTranslate\Jobs\TranslatorJob;

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
        $attr = parent::getAttribute($key);

        // Model field we are trying to access is not in the array of 
        // columns to translate so we skip.
        if( ! in_array( $key, $this->translateColumns ) )
            return $attr;
        

        // Check if and attribute overrider method is defined in the model. 
        // This is useful for resolving trait collisions.
        if( method_exists( $this, 'getAttributeOverrider' ) )
        {
            $attr = $this->getAttributeOverrider($key);
        }

        try{
            $translationModel = Translation::whereModel( $this->getTranslationModelClassName() )
                ->whereModelId( $this->id )
                ->where( 'column', $key )
                ->whereLocale( EloquentTranslate::getLocale() )
                ->first();

            return $translationModel ? $translationModel->translation : $attr;

        } catch(\Exception $e) {

            // Do something here if an error occurs 
        }
        
        return $attr;
    }

    public function translate($force = false)
    {
        $model = $this;

        foreach( $model->translateColumns as $column ){

            $value = $model->{$column};

            if( $value )
            {
                // Fetch and store model translations from  Translate
                foreach( config('eloquent-translate.locales') as $locale )
                {
                    // In order to avoid repeating translations, we check if 
                    // the value already exists, if it does, we skip translations.
                    // However, model events like created and updated will override this.
                    if( $force !== true )
                    {
                        // Check if translation exists, if it does, break out of the loop
                        if( $this->translations()
                            ->where('locale', $locale) 
                            ->where('column', $column)
                            ->count() > 0
                        )
                        {
                            break;
                        }
                    }

                    // Check if queue was enabled and process with queue 
                    if( config('eloquent-translate.queue') === true){

                        // Disatch the job 
                        dispatch( new TranslatorJob( $model, $column, $locale ) );
                    }
                    else {

                        // Run without queue 
                        ( new Translator( $model, $column, $locale ) )->saveTranslation();
                    }
                }
            }
        }
    }

    public function translations()
    {
        return $this->hasMany( Translation::class, 'model_id', 'id' )->where('model', $this->getTranslationModelClassName() );
    }
}
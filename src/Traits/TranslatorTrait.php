<?php 

namespace PaschalDev\EloquentTranslate\Traits;

use PaschalDev\EloquentTranslate\Jobs\TranslatorJob;
use PaschalDev\EloquentTranslate\Models\Translation;
use PaschalDev\EloquentTranslate\Services\Translator;
use PaschalDev\EloquentTranslate\TranslateModelObserver;
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
        $attr = parent::getAttribute($key);

        // Model field we are trying to access is not in the array of 
        // attributes to translate so we skip.
        if( ! in_array( $key, $this->getTranslationAttributes() ) )
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
                ->where( 'attribute', $key )
                ->whereLocale( EloquentTranslate::getLocale() )
                ->first();

            return $translationModel ? $translationModel->translation : $attr;

        } catch(\Exception $e) {}
        
        return $attr;
    }

    public function translate($force = false)
    {
        $model = $this;

        foreach( $model->getTranslationAttributes() as $attribute ){

            $value = $model->{$attribute};

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
                            ->where('attribute', $attribute)
                            ->count() > 0
                        )
                        {
                            break;
                        }
                    }

                    // Check if queue was enabled and process with queue 
                    if( config('eloquent-translate.queue') === true){

                        // Disatch the job 
                        dispatch( new TranslatorJob( $model, $attribute, $locale ) );
                    }
                    else {

                        // Run without queue 
                        ( new Translator( $model, $attribute, $locale ) )->saveTranslation();
                    }
                }
            }
        }

        return true;
    }
    
    /**
     * Set translations manually on the model.
     * 
     * @return Boolean|Illuminate\Database\Eloquent\Model
     */
    public function setTranslation($attribute, $locale, $translation, $force = false)
    {   
        // Check if the attribute name is defined in the model. If it's not, 
        // and the $force parameter is not true, we skip.

        if( ! $force && ! in_array( $attribute, $this->getTranslationAttributes() ) )
            return false;

        return $this->translations()->updateOrCreate(
            [
                'model' => $this->getTranslationModelClassName(),
                'model_id' => $this->id,
                'attribute' => $attribute,
                'locale' => $locale,
            ],
            [
                'translation' => $translation
            ]
        );
    }
    
    /**
     * Set translations manually on the model.
     * 
     * @return Boolean|Illuminate\Database\Eloquent\Model
     */
    public function setTranslations($attribute, $translations)
    {   
        // Check if the attribute name is defined in the model. If it's not, 
        // and the $force parameter is not true, we skip.

        if(! in_array( $attribute, $this->getTranslationAttributes() ) )
            return false;
        
        collect($translations)->each(function($translation, $locale) use($attribute) {

            $this->translations()->updateOrCreate(
                [
                    'model' => $this->getTranslationModelClassName(),
                    'model_id' => $this->id,
                    'attribute' => $attribute,
                    'locale' => $locale,
                ],
                [
                    'translation' => $translation
                ]
            );
        });

        return true;
    }

    /**
     * Fetch translations for a given model.
     * 
     * @return Illuminate\Database\Eloquent\Model|Illuminate\Support\Collection
     */
    public function getTranslation($attribute, $locale, $default = null )
    {
        $translation = $this->translations()
            ->where('attribute', $attribute)
            ->where('locale', $locale);

        if( $translation->count() > 0 )
        {
            return $translation->first()->translation;
        }

        return $default;
    }

    /**
     * Check if model has translation for a given locale and attribute
     * 
     * @return Boolean
     */
    public function hasTranslation( $attribute, $locale )
    {
        $translation = $this->translations()
            ->where('attribute', $attribute)
            ->where('locale', $locale);

        return $translation->count() > 0;
    }

    /**
     * Fetch translations for a given model and a locale.
     * 
     * @return Illuminate\Support\Collection
     */
    public function getTranslations($locale)
    {
        return $this->translations()->where('locale', $locale)->get();
    }

    /**
     * The translation relationship.
     * 
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function translations()
    {
        return $this->hasMany( Translation::class, 'model_id', 'id' )->where('model', $this->getTranslationModelClassName() );
    }

    /**
     * Get the attributes to be translateable
     * 
     * @return array
     */
    public function getTranslationAttributes()
    {
        return is_array( $this->translateAttributes )
            ? $this->translateAttributes
            : [];
    }

    /**
     * Delete model translations by locale
     * 
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function deleteTranslation($attribute, $locale)
    {
        return $this->translations()
            ->where('attribute', $attribute)
            ->where('locale', $locale)
            ->delete();
    }

    public function deleteAllTranslations()
    {
        return $this->translations()->delete();
    }
}
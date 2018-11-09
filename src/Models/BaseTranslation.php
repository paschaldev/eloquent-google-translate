<?php 

namespace PaschalDev\EloquentTranslate\Models;

use Illuminate\Database\Eloquent\Model;
use PaschalDev\EloquentTranslate\TranslateModelObserver;

class BaseTranslation extends Model {

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
        // dd( $key, $this->translateColumns );

        if( in_array( $key, $this->translateColumns ) )
        {

            // dd( $this->getAttribute( $key ) );
            return $attr;
            // return 'Talk';
        }

        return $key;
    }
}
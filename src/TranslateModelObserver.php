<?php 

namespace PaschalDev\EloquentTranslate;

use Illuminate\Database\Eloquent\Model;
use PaschalDev\EloquentTranslate\Jobs\TranslatorJob;
use PaschalDev\EloquentTranslate\Services\Translator;
use PaschalDev\EloquentTranslate\Models\Translation;

class TranslateModelObserver
{

    private $columns = [];

    private $model;

    /**
     * Handle the User "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(Model $model)
    {
        $this->translate( $model );

        return $model;
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(Model $model)
    {
        $this->translate( $model );

        return $model;
    }

    /**
     * Delete translations for a model when the model is deleted
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(Model $model)
    {
        $modelClass = get_class( $model );

        Translation::where('model', $modelClass)
            ->where('model_id', $model->id)
            ->delete();
    }

    private function setColumns($columns)
    {
        if( ! is_array($columns) ){
            return;
        }

        $this->columns = $columns;
    }

    private function translate(Model $model, $force = false)
    {
        $this->model = $model;
        $this->setColumns( $model->translateColumns );

        foreach( $this->columns as $column ){

            $value = $model->{$column};

            if( $value )
            {
                // Fetch and store model translations from  Translate
                foreach( config('eloquent-translate.locales') as $locale )
                {
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
}
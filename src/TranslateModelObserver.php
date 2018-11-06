<?php 

namespace PaschalDev\EloquentGoogleTranslate;

use Illuminate\Database\Eloquent\Model;
use PaschalDev\EloquentGoogleTranslate\Models\Translation;

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
     * Handle the User "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(Model $model)
    {
        //
    }

    private function setColumns($columns)
    {
        if( ! is_array($columns) ){
            return;
        }

        $this->columns = $columns;
    }

    private function translate(Model $model)
    {
        $this->model = $model;
        $this->setColumns( $model->translateColumns );

        $modelClass = (new \ReflectionClass($model))->getName();

        foreach( $this->columns as $column ){

            $value = $model->{$column};

            if( $value )
            {
                // Fetch and store model translations from Google Translate
                Translation::updateOrCreate(
                    [
                        'model' => $modelClass,
                        'model_id' => $model->id,
                        'column' => $column,
                        'locale' => 'fr',
                    ],
                    [
                        'translation' => 'Soulignez le mot convenable'
                    ]
                );
            }
        }
    }
}
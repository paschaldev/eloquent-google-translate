<?php 

namespace PaschalDev\EloquentTranslate\Services;

use PaschalDev\EloquentTranslate\Models\Translation;
use Illuminate\Database\Eloquent\Model;

class Translator {

    public function __construct(Model $model, $column, $locale)
    {
        $this->model = $model;
        $this->column = $column;
        $this->locale = $locale;
    }

    public function saveTranslation(){

        Translation::updateOrCreate(
            [
                'model' => get_class( $this->model ),
                'model_id' => $this->model->id,
                'column' => $this->column,
                'locale' => $this->locale,
            ],
            [
                'translation' => $this->getTranslation( $this->model->{$this->column}, $this->locale )
            ]
        );
    }

    /**
     * Gets the translation string
     *
     * @return string
     */
    public function getTranslation($text, $locale)
    {   
        $translatorService = new GoogleTranslate;

        return $translatorService->translate($text, $locale);
    }
}
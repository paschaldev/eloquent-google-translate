<?php 

namespace PaschalDev\EloquentTranslate\Traits;

use PaschalDev\EloquentTranslate\TranslateModelObserver;

trait TranslatorTrait {

    public static function getTranslationModelClassName()
    {
        return get_class();
    }
}
<?php 

namespace PaschalDev\EloquentTranslate\Contracts;

interface TranslatorContract {

    public function translate($text, $locale);
}
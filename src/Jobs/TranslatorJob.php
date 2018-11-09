<?php

namespace PaschalDev\EloquentTranslate\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use PaschalDev\EloquentTranslate\Services\Translator;

class TranslatorJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    public $column;
    public $locale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $column, $locale)
    {
        $this->model = $model;
        $this->column = $column;
        $this->locale = $locale;
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ( new Translator( $this->model, $this->column, $this->locale ) )->saveTranslation();
    }
}
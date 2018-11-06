<?php

namespace PaschalDev\EloquentGoogleTranslate\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use PaschalDev\EloquentGoogleTranslate\EloquentGoogleTranslate;

use PaschalDev\EloquentGoogleTranslate\TranslateModelObserver;

class TranslateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->eloquentTranslatePublishes();
        $this->eloquentTranslateConfig();
        $this->eloquentTranslateMigrations();
        $this->eloquentTranslateHelpers();
        $this->setupObservers();
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('eloquent-translate', function () {
            return new EloquentGoogleTranslate;
        });
    }
    /**
     * Items to be published
     *
     * @return void
     */
    protected function eloquentTranslatePublishes()
    {
        $this->publishes([
            __DIR__ . '/../config/eloquent-google-translate.php' => config_path('eloquent-google-translate.php'),
        ]);
    }
    /**
     * Marge the package's configuration.
     *
     * @return void
     */
    protected function eloquentTranslateConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/eloquent-google-translate.php',
            'eloquent-google-translate');
    }
    
    /**
     * Load migrations.
     *
     * @return void
     */
    protected function eloquentTranslateMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
    /**
     * Register helpers file
     *
     * @return void
     */
    protected function eloquentTranslateHelpers()
    {
        require_once __DIR__ . '/../helpers.php';
    }

    protected function setupObservers()
    {
        foreach( config('eloquent-google-translate.models') as $model => $columns)
        {
            $model::observe( new TranslateModelObserver );
        }
    }
}
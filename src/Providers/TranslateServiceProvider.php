<?php

namespace PaschalDev\EloquentTranslate\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use PaschalDev\EloquentTranslate\EloquentTranslate;

use PaschalDev\EloquentTranslate\TranslateModelObserver;

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
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('eloquent-translate', function () {

            $request = app(\Illuminate\Http\Request::class);

            dd( $_COOKIE );

            return new EloquentTranslate( $request->cookie('locale') );
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
            __DIR__ . '/../config/eloquent-translate.php' => config_path('eloquent-translate.php'),
        ]);
    }
    /**
     * Marge the package's configuration.
     *
     * @return void
     */
    protected function eloquentTranslateConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/eloquent-translate.php',
            'eloquent-translate');
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
}
<?php

namespace FilePreviews\Laravel;

use FilePreviews;
use Illuminate\Support\ServiceProvider;

class FilePreviewsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../config/filepreviews.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('filepreviews.php')]);
        }

        $this->mergeConfigFrom($source, 'filepreviews');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('FilePreviews', function ($app) {
            $config = $app['config']->get('filepreviews');
            return new FilePreviews\FilePreviews($config);
        });

         $this->app->alias('FilePreviews', 'FilePreviews\FilePreviews');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['FilePreviews', 'FilePreviews\FilePreviews'];
    }
}

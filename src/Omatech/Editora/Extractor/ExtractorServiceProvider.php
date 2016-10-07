<?php

namespace Omatech\Editora\Extractor;

use Illuminate\Support\ServiceProvider;
use Omatech\Editora\Extractor\Extractor;

class ExtractorServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Extractor', function ($app) {
            return new Extractor([
            'dbname' => env('DB_DATABASE', 'forge'),
            'dbuser' => env('DB_USERNAME', 'forge'),
            'dbpass' => env('DB_PASSWORD', ''),
            'dbhost' => env('DB_HOST', 'localhost'),
        ]);
        });
    }
}


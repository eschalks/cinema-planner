<?php

namespace App\Providers;

use App\MovieSources\Pathe\PatheMovieSource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (config('movies.sources') as $sourceClass) {
            $this->app->singleton($sourceClass);
            $this->app->tag($sourceClass, 'movie_source');
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'App\Repositories\RepositoryInterface',
            'App\Repositories\Repository'
        );

        $this->app->singleton(
            'App\Repositories\Users\UserRepositoryInterface',
            'App\Repositories\Users\UserRepository'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

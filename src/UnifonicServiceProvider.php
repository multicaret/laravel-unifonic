<?php

namespace Multicaret\Unifonic;

use Illuminate\Support\ServiceProvider;


class UnifonicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {

    }


    /**
     * Register the application services.
     *
     * @return void
     */

    public function register()
    {
        $this->app->singleton('unifonic', function () {
            return new UnifonicManager;
        });
    }

}

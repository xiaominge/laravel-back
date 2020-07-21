<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Foundation\Context\ContextHandler;
use App\Foundation\Response\BusinessHandler;
use App\Foundation\Response\UserBusinessHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('context', function () {
            return ContextHandler::getInstance();
        });
        $this->app->singleton('businessHandler', function () {
            return new BusinessHandler();
        });
        $this->app->singleton('userBusinessHandler', function () {
            return new UserBusinessHandler();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton('server_ip_no', function () {
            return '192.168.0.111'; 
        });
        $this->app->singleton('server_ip', function () {
            return 'http://192.168.0.111'; 
        });
        $this->app->singleton('server_post', function () {
            return '8000'; 
        });
        $this->app->singleton('user', function () {
            return asset('assets/img/user.png'); 
        });
        $this->app->singleton('app-icon', function () {
            return asset('assets/logo/app-icon.png'); 
        });
        $this->app->singleton('app-icon-text', function () {
            return asset('assets/logo/app-icon-text.png'); 
        });
        $this->app->singleton('app-icon-width', function () {
            return asset('assets/logo/app-icon-width.png'); 
        });
    }
}

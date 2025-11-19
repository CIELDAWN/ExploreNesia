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
    public function boot()
    {
        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('user', \App\Http\Middleware\UserMiddleware::class);

        // Jika admin dan mitra middleware belum terdaftar, register juga
        $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        $router->aliasMiddleware('mitra', \App\Http\Middleware\MitraMiddleware::class);
    }
}

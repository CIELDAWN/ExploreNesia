<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Review;
use App\Observers\BookingObserver;
use App\Observers\ReviewObserver;
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

        Booking::observe(BookingObserver::class);
        Review::observe(ReviewObserver::class);
    }
}

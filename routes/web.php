<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReviewController;

use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
use App\Http\Controllers\Mitra\GeoController;
use App\Http\Controllers\Mitra\HotelController as MitraHotelController;
use App\Http\Controllers\Mitra\RestaurantController as MitraRestaurantController;
use App\Http\Controllers\Mitra\BookingController as MitraBookingController;
use App\Http\Controllers\Mitra\ReviewController as MitraReviewController;
use App\Http\Controllers\Mitra\PromotionController as MitraPromotionController;
use App\Http\Controllers\Mitra\NotificationController as MitraNotificationController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DestinationController as UserDestinationController;
use App\Http\Controllers\User\FavoriteController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard sesuai role
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'mitra') {
            return redirect()->route('mitra.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes - Protected by auth and admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Destination Management
    Route::resource('destinations', DestinationController::class);
    Route::patch('destinations/{destination}/approve', [DestinationController::class, 'approve'])->name('destinations.approve');
    Route::patch('destinations/{destination}/reject', [DestinationController::class, 'reject'])->name('destinations.reject');

    // Hotel Management
    Route::resource('hotels', HotelController::class);
    Route::patch('hotels/{hotel}/approve', [HotelController::class, 'approve'])->name('hotels.approve');
    Route::patch('hotels/{hotel}/reject', [HotelController::class, 'reject'])->name('hotels.reject');

    // Restaurant Management
    Route::resource('restaurants', RestaurantController::class);
    Route::patch('restaurants/{restaurant}/approve', [RestaurantController::class, 'approve'])->name('restaurants.approve');
    Route::patch('restaurants/{restaurant}/reject', [RestaurantController::class, 'reject'])->name('restaurants.reject');

    // Review Management
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');

    // Analytics & Reports
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
});

// Mitra Routes - Protected by auth and mitra middleware
Route::middleware(['auth', 'mitra'])->prefix('mitra')->name('mitra.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');

    // Content management
    Route::resource('hotels', MitraHotelController::class);
    Route::resource('restaurants', MitraRestaurantController::class);

    // Promotions
    Route::resource('promotions', MitraPromotionController::class)->except(['show']);

    // Bookings management
    Route::get('/bookings', [MitraBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [MitraBookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Reviews
    Route::get('/reviews', [MitraReviewController::class, 'index'])->name('reviews.index');

    // Notifications
    Route::get('/notifications', [MitraNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [MitraNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [MitraNotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::post('/geo/sync-city', [GeoController::class, 'syncCity'])->name('geo.sync-city');
});

// User Routes - Protected by auth
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Destinations
    Route::get('/destinations', [UserDestinationController::class, 'index'])->name('destinations');
    Route::get('/destinations/{slug}', [UserDestinationController::class, 'show'])->name('destinations.show');

    // Favorites
    Route::post('/favorites/destination/{destination}', [FavoriteController::class, 'toggleDestination'])->name('favorites.toggle-destination');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Register Routes - INI YANG PENTING!
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home/Welcome
Route::get('/', function () {
    return view('welcome');
})->name('home');

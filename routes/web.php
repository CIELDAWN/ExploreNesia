<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MitraSubmissionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\DestinationController as UserDestinationController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use App\Http\Controllers\User\TripController as UserTripController;
use App\Http\Controllers\User\HotelController as UserHotelController;
use App\Http\Controllers\User\RestaurantController as UserRestaurantController;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
use App\Http\Controllers\Mitra\BookingController as MitraBookingController;
use Illuminate\Support\Facades\Route;
use App\Models\Mitra;

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
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isMitra()) {
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
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
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
    
    // Mitra Submissions Management (Unified: Destinations, Hotels, Restaurants)
    Route::get('mitra-submissions', [MitraSubmissionController::class, 'index'])->name('mitra-submissions.index');
    Route::post('mitra-submissions/approve', [MitraSubmissionController::class, 'approve'])->name('mitra-submissions.approve');
    Route::post('mitra-submissions/reject', [MitraSubmissionController::class, 'reject'])->name('mitra-submissions.reject');
    Route::post('mitra-submissions/destroy', [MitraSubmissionController::class, 'destroy'])->name('mitra-submissions.destroy');
    
    // Review Management
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    
    // Analytics & Reports
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
});

// Mitra Routes - Protected by auth and mitra middleware (Coming soon)
Route::middleware(['auth', 'mitra'])->prefix('mitra')->name('mitra.')->group(function () {
    Route::get('/dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');
    Route::get('/create', [MitraDashboardController::class, 'create'])->name('create');
    Route::post('/store', [MitraDashboardController::class, 'store'])->name('store');
    Route::get('/edit', [MitraDashboardController::class, 'edit'])->name('edit');
    Route::put('/update', [MitraDashboardController::class, 'update'])->name('update');

    Route::get('/bookings', [MitraBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/confirm', [MitraBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject', [MitraBookingController::class, 'reject'])->name('bookings.reject');
});

// User Routes - Protected by auth (Basic user access)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('profile');
    
    // Destinations & Bookings
    Route::get('/destinations', [UserDestinationController::class, 'index'])->name('destinations');
    Route::get('/destinations/{slug}', [UserDestinationController::class, 'show'])->name('destinations.show');

    Route::get('/hotels/{slug}', [UserHotelController::class, 'show'])->name('hotels.show');

    Route::get('/restaurants/{slug}', [UserRestaurantController::class, 'show'])->name('restaurants.show');
    
    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Booking routes (explicit) - protected by auth middleware
Route::middleware(['auth'])->group(function () {
    Route::post('/user/destinations/{slug}/book', [UserBookingController::class, 'storeDestination'])
        ->name('user.destinations.book');

    Route::post('/user/hotels/{slug}/book', [UserBookingController::class, 'storeHotel'])
        ->name('user.hotels.book');

    Route::post('/user/restaurants/{slug}/book', [UserBookingController::class, 'storeRestaurant'])
        ->name('user.restaurants.book');

    // Trip history
    Route::get('/user/trips', [UserTripController::class, 'index'])->name('user.trips.index');
    Route::post('/user/trips/{booking}/complete', [UserTripController::class, 'complete'])->name('user.trips.complete');
});
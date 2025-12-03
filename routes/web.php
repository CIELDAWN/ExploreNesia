<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AnalyticsController;

use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DestinationController as UserDestinationController;
use App\Http\Controllers\User\FavoriteController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ExploreNesia
|--------------------------------------------------------------------------
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// Homepage / Welcome
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

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

// Guest Only Routes (Login & Register)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Logout (Authenticated Users Only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========================================
    // User Management
    // ========================================
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::patch('users/{user}/toggle-verification', [UserController::class, 'toggleEmailVerification'])
        ->name('users.toggle-verification');

    // ========================================
    // Category Management
    // ========================================
    Route::resource('categories', CategoryController::class);

    // ========================================
    // Tag Management
    // ========================================
    Route::resource('tags', TagController::class);

    // ========================================
    // Destination Management
    // ========================================
    Route::resource('destinations', DestinationController::class);
    Route::patch('destinations/{destination}/approve', [DestinationController::class, 'approve'])
        ->name('destinations.approve');
    Route::patch('destinations/{destination}/reject', [DestinationController::class, 'reject'])
        ->name('destinations.reject');

    // ========================================
    // Hotel Management
    // ========================================
    Route::resource('hotels', HotelController::class);
    Route::patch('hotels/{hotel}/approve', [HotelController::class, 'approve'])
        ->name('hotels.approve');
    Route::patch('hotels/{hotel}/reject', [HotelController::class, 'reject'])
        ->name('hotels.reject');

    // ========================================
    // Restaurant Management
    // ========================================
    Route::resource('restaurants', RestaurantController::class);
    Route::patch('restaurants/{restaurant}/approve', [RestaurantController::class, 'approve'])
        ->name('restaurants.approve');
    Route::patch('restaurants/{restaurant}/reject', [RestaurantController::class, 'reject'])
        ->name('restaurants.reject');

    // ========================================
    // Review Management
    // ========================================
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])
        ->name('reviews.approve');

    // ========================================
    // Analytics & Reports
    // ========================================

    // Analytics Page
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');

    // Analytics API Endpoints (AJAX)
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/realtime-stats', [AnalyticsController::class, 'getRealtimeStats'])
            ->name('realtime-stats');
        Route::get('/booking-trend', [AnalyticsController::class, 'getBookingTrend'])
            ->name('booking-trend');
        Route::get('/revenue-stats', [AnalyticsController::class, 'getRevenueStats'])
            ->name('revenue-stats');
        Route::get('/top-mitra', [AnalyticsController::class, 'getTopMitra'])
            ->name('top-mitra');
        Route::get('/recent-activities', [AnalyticsController::class, 'getRecentActivities'])
            ->name('recent-activities');
    });

    // Reports Page
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
});

// ============================================================================
// MITRA ROUTES
// ============================================================================

Route::middleware(['auth', 'mitra'])->prefix('mitra')->name('mitra.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');

    // Business Management
    Route::get('/create', [MitraDashboardController::class, 'create'])->name('create');
    Route::post('/store', [MitraDashboardController::class, 'store'])->name('store');
    Route::get('/edit', [MitraDashboardController::class, 'edit'])->name('edit');
    Route::put('/update', [MitraDashboardController::class, 'update'])->name('update');
});

// ============================================================================
// USER ROUTES
// ============================================================================

Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Destinations
    Route::get('/destinations', [UserDestinationController::class, 'index'])->name('destinations');
    Route::get('/destinations/{slug}', [UserDestinationController::class, 'show'])
        ->name('destinations.show');

    // Favorites
    Route::post('/favorites/destination/{destination}', [FavoriteController::class, 'toggleDestination'])
        ->name('favorites.toggle-destination');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');
});

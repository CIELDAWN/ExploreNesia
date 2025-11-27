<?php

use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\VisitHistoryController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\Mitra\ReviewController as MitraReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// User (Pelanggan) API Routes
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

    // Favorites API
    Route::prefix('favorites')->name('api.favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('/', [FavoriteController::class, 'store']);
        Route::delete('/{id}', [FavoriteController::class, 'destroy']);
        Route::get('/check/{destinationId}', [FavoriteController::class, 'check']);
        Route::post('/toggle', [FavoriteController::class, 'toggle']);
    });

    // Visit Histories API
    Route::prefix('visit-histories')->name('api.visit-histories.')->group(function () {
        Route::get('/', [VisitHistoryController::class, 'index']);
        Route::post('/', [VisitHistoryController::class, 'store']);
        Route::get('/{id}', [VisitHistoryController::class, 'show']);
        Route::put('/{id}', [VisitHistoryController::class, 'update']);
        Route::delete('/{id}', [VisitHistoryController::class, 'destroy']);
        Route::get('/statistics', [VisitHistoryController::class, 'statistics']);
    });

    // Reviews API
    Route::prefix('reviews')->name('api.reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::get('/{id}', [ReviewController::class, 'show']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
    });
});

// Mitra Review Moderation API
Route::middleware(['auth:sanctum', 'role:mitra'])->prefix('mitra/reviews')->group(function () {
    Route::get('/', [MitraReviewController::class, 'index']);
    Route::get('/{id}', [MitraReviewController::class, 'show']);
    Route::post('/{id}/approve', [MitraReviewController::class, 'approve']);
    Route::post('/{id}/reject', [MitraReviewController::class, 'reject']);
    Route::post('/bulk-approve', [MitraReviewController::class, 'bulkApprove']);
    Route::delete('/{id}', [MitraReviewController::class, 'destroy']);
    Route::get('/statistics', [MitraReviewController::class, 'statistics']);
});

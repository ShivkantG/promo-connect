<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Brand\BookingController;
use App\Http\Controllers\Brand\BrandProfileController;
use App\Http\Controllers\Brand\EventController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Promoter\EventParticipationController;
use App\Http\Controllers\Promoter\PromoterController;
use App\Http\Controllers\Promoter\PromoterPostController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::get('/test', [AuthController::class, 'test']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
});


Route::middleware(['auth:sanctum'])->group(function () {


    Route::prefix('v1')->group(function () {

        // promoter api
        Route::prefix('promoter')->group(function () {
            Route::get('/profile', [ProfileController::class, 'show']);
            Route::put('/profile', [ProfileController::class, 'update']);

            Route::post('/profile/upload-document', [ProfileController::class, 'uploadDocument']);
            // Route::get('/profile/stats', [ProfileController::class, 'stats']);
            Route::post('/profile/portfolio', [PortfolioController::class, 'store']);
            Route::put('/profile/portfolio/{id}', [PortfolioController::class, 'update']);
            Route::delete('/profile/portfolio/{id}', [PortfolioController::class, 'destroy']);

            Route::post('/promoter/posts', [PromoterPostController::class, 'store']);
            Route::get('/promoter/posts', [PromoterPostController::class, 'index']);
            // Route::delete('/promoter/media', [PromoterController::class, 'deleteMedia']);
            Route::post(
                '/bookings/{id}/participation',
                [EventParticipationController::class, 'store']
            );
        });
        // brand profile
        Route::prefix('brand')->group(function () {

            Route::get('/profile', [BrandProfileController::class, 'show']);
            Route::put('/profile', [BrandProfileController::class, 'update']);
            Route::post('/profile/upload-docs', [BrandProfileController::class, 'uploadDocs']);

            Route::get('/test', [EventController::class, 'test']);
            Route::get('/events', [EventController::class, 'index']);
            Route::post('/events', [EventController::class, 'store']);
            Route::put('/events/{id}', [EventController::class, 'update']);
            Route::delete('/events/{id}', [EventController::class, 'destroy']);

            Route::post('/bookings', [BookingController::class, 'store']);
            Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus']);
            Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
        });

        Route::post('/chat/send', [ChatController::class, 'sendMessage']);
        Route::get('/chat/{conversationId}', [ChatController::class, 'getMessages']);
    });
});

// Route::get('/auth/instagram/redirect', [InstagramController::class, 'redirect']);
// Route::get('/auth/instagram/callback', [InstagramController::class, 'callback']);

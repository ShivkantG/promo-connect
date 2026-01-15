<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;



// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);



Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/test', [AuthController::class, 'test']);
// });

Route::prefix('v1')->group(
    function () {
        Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
            Route::get('/test', [AdminController::class, 'test']);
        });
    }
);


// Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
//     Route::get('/test', [AuthController::class, 'test']);
// });




// Route::get('/test', function () {
//     return "hello";
// });

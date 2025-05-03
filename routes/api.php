<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\FactoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\StartUpController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Factory\StartupRequestController as FactoryStartupRequestController;
use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});
//             factory login
Route::controller(\App\Http\Controllers\Api\Factory\AuthController::class)->group(function () {
    Route::post('factory/login', 'login');
});

Route::middleware('auth:api')->group(function () {
    //admin routes
    Route::prefix('admin')->group(function () {
        Route::resource('user', UserController::class)->only(['index', 'show', 'destroy']);
        Route::get('user/{id}/checkDestroy', [UserController::class, 'checkDestroy']);

        Route::resource('startup', StartUpController::class)->only(['index', 'show', 'destroy']);
        Route::put('startup/{id}/block', [StartUpController::class, 'block']);
        Route::post('startup/{id}/accept', [StartUpController::class, 'accept']);
        Route::post('startup/{id}/reject', [StartUpController::class, 'reject']);

        Route::resource('category', CategoryController::class)->except(['create', 'edit']);
        Route::resource('subcategory', SubCategoryController::class)->except(['create', 'edit']);

        Route::resource('factory', FactoryController::class)->except(['create', 'edit', 'update']);
        Route::put('factory/{id}/block', [FactoryController::class, 'block']);

        Route::resource('product', ProductController::class)->only(['index', 'show', 'destroy']);
    });
    // factory routes
    Route::middleware('auth:factory')->group(function () {
        Route::prefix('factory')->group(function () {
            Route::resource('request', FactoryStartupRequestController::class)->only(['index', 'show', 'destroy']);
        });
    });
});

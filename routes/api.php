<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\FactoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\ResponseController as AdminResponseController;
use App\Http\Controllers\Api\Admin\StartUpController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Factory\ResponseController as FactoryResponseController;
use App\Http\Controllers\Api\Factory\StartupRequestController as FactoryStartupRequestController;
use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\ProductController as UserProductController;



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
        Route::put('user/{id}/block', [UserController::class, 'block']);

        Route::resource('startup', StartUpController::class)->only(['index', 'show', 'destroy']);
        Route::put('startup/{id}/block', [StartUpController::class, 'block']);
        Route::post('startup/{id}/accept', [StartUpController::class, 'accept']);
        Route::post('startup/{id}/reject', [StartUpController::class, 'reject']);

        Route::resource('category', CategoryController::class)->except(['create', 'edit']);
        Route::resource('subcategory', SubCategoryController::class)->except(['create', 'edit']);

        Route::resource('factory', FactoryController::class)->except(['create', 'edit', 'update']);
        Route::put('factory/{id}/block', [FactoryController::class, 'block']);

        Route::resource('product', ProductController::class)->only(['index', 'show', 'destroy']);

        Route::resource('response', AdminResponseController::class)->only(['index', 'show']);
    });

    
    Route::prefix('user')->group(function () {
        Route::get('products', [UserProductController::class, 'index']);

    });
});

// factory routes
Route::middleware('auth:factory')->group(function () {
    Route::prefix('factory')->group(function () {
        Route::resource('request', FactoryStartupRequestController::class)->only(['index', 'show', 'destroy']);

        Route::resource('response', FactoryResponseController::class)->only(['index', 'show', 'destroy']);
        Route::post('response/send-offer', [FactoryResponseController::class, 'sendOffer']);
    });
});


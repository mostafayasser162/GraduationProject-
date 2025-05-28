<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\User\ReviewController;
use App\Http\Controllers\Api\Admin\FactoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\StartUpController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\Startup\AuthController as StartupAuthController;
use App\Http\Controllers\Api\Startup\ProfileController as StartupProfileController;
use App\Http\Controllers\Api\User\OrderController as UserOrderController;
use App\Http\Controllers\Api\User\AddressController as UserAddressController;
use App\Http\Controllers\Api\User\ProductController as UserProductController;
use App\Http\Controllers\Api\User\ProfileController as UserProfileController;
use App\Http\Controllers\Api\User\StartUpController as UserStartUpController;
use App\Http\Controllers\Api\User\WishlistController as UserWishlistController;
use App\Http\Controllers\Api\Admin\ResponseController as AdminResponseController;
use App\Http\Controllers\Api\Factory\ResponseController as FactoryResponseController;
use App\Http\Controllers\Api\Factory\StartupRequestController as FactoryStartupRequestController;

// use App\Http\Controllers\WishlistController as UserWishlistController;

//login for user and admin
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('verify-otp', 'verifyOtp');
    Route::post('resend-otp', 'resendOtp');
    Route::post('forgot-password', 'forgetPassword');
    Route::post('reset-password', 'resetPassword');
});


//                                    user and admin routes
Route::middleware('auth:api')->group(function () {
    //                                   admin routes
    Route::prefix('admin')->group(function () {
        Route::resource('user', UserController::class)->only(['index', 'show', 'destroy']);
        Route::get('user/{id}/checkDestroy', [UserController::class, 'checkDestroy']);
        Route::put('user/{id}/block', [UserController::class, 'block']);


        Route::resource('startups', StartUpController::class)->only(['index', 'show', 'destroy']);

        Route::put('startup/{id}/block', [StartUpController::class, 'block']);
        Route::post('startup/{id}/accept', [StartUpController::class, 'accept']);
        Route::post('startup/{id}/reject', [StartUpController::class, 'reject']);

        Route::resource('category', CategoryController::class)->except(['create', 'edit']);
        Route::resource('subcategory', SubCategoryController::class)->except(['create', 'edit']);

        Route::resource('factory', FactoryController::class)->except(['create', 'edit', 'update']);
        Route::put('factory/{id}/block', [FactoryController::class, 'block']);

        Route::resource('product', ProductController::class)->only(['index', 'show', 'destroy']);


        Route::resource('responses', AdminResponseController::class)->only(['index', 'show']);
    });
    //                                   user routes
    Route::prefix('user')->group(function () {

        Route::resource('products', UserProductController::class)->only(['index', 'show']);

        //profile
        Route::controller(UserProfileController::class)->group(function () {
            Route::get('/profile', 'index');
            Route::put('/profile', 'update');
            Route::delete('/profile', 'destroy');
        });

        Route::resource('startup', UserStartUpController::class)->only(['index', 'show']);

        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'addToCart']);
        // Route::put('/cart/update', [CartController::class, 'updateQuantity']);
        Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
        Route::delete('/cart/clear', [CartController::class, 'clearCart']);

        Route::post('orders/place', [UserOrderController::class, 'placeOrder']);
        Route::get('/orders', [UserOrderController::class, 'index']);
        Route::get('/orders/{orderId}', [UserOrderController::class, 'show']);

        //   Add product to wishlist
        Route::post('/wishlist/{productId}', [UserWishlistController::class, 'addToWishlist']);

        // Get user's wishlist
        Route::get('/wishlist', [UserWishlistController::class, 'getWishlist']);

        // Remove product from wishlist
        Route::delete('/wishlist/{productId}', [UserWishlistController::class, 'removeFromWishlist']);
        Route::resource('/addresses', UserAddressController::class)->only(['index', 'store', 'destroy']);
        // route to update address
        Route::put('/addresses/{id}', [UserAddressController::class, 'update']);

        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::get('/products/{productId}/reviews', [ReviewController::class, 'productReviews']);


        // Register for startup
        Route::post('/startup/register', [StartupAuthController::class, 'register']);
    });
});

//                                       factory routes
//                                       factory login
Route::controller(\App\Http\Controllers\Api\Factory\AuthController::class)->group(function () {
    Route::post('factory/login', 'login');
});
Route::middleware('auth:factory')->group(function () {
    Route::prefix('factory')->group(function () {
        Route::resource('request', FactoryStartupRequestController::class)->only(['index', 'show', 'destroy']);

        Route::resource('response', FactoryResponseController::class)->only(['index', 'show', 'destroy']);

        Route::post('response/send-offer', [FactoryResponseController::class, 'sendOffer']);
    });
});

//                                       startup routes
//                                       startup login
Route::controller(StartupAuthController::class)->group(function () {
    Route::post('startup/login', 'login');
});
Route::middleware('auth:startup')->group(function () {
    Route::prefix('startup')->group(function () {
        Route::resource('request', FactoryStartupRequestController::class)->only(['index', 'show', 'destroy']);

        Route::resource('response', FactoryResponseController::class)->only(['index', 'show', 'destroy']);

        Route::post('response/send-offer', [FactoryResponseController::class, 'sendOffer']);
        //profile
        Route::controller(StartupProfileController::class)->group(function () {
            Route::get('/profile', 'index');
            Route::put('/profile', 'update');
            Route::delete('/profile', 'destroy');
        });
    });
});


//                                       general routes
Route::prefix('general')->group(function () {
    Route::resource('products', UserProductController::class)->only(['index', 'show']);
});

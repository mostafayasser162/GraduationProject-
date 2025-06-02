<?php

use App\Http\Controllers\Api\Startup\RatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\DealController as AdminDealController;
use App\Http\Controllers\Api\Factory\DealController as FactoryDealController;
use App\Http\Controllers\Api\Startup\DealController as StartupDealController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\User\ReviewController;
use App\Http\Controllers\Api\StartUp\SizeController;
use App\Http\Controllers\Api\Admin\FactoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\StartUpController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\StartUp\RequestController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\General\SubCategoryController as GeneralSubCategoryController;
use App\Http\Controllers\Api\User\OrderController as UserOrderController;
use App\Http\Controllers\Api\Startup\AuthController as StartupAuthController;
use App\Http\Controllers\Api\User\AddressController as UserAddressController;
use App\Http\Controllers\Api\User\ProductController as UserProductController;
use App\Http\Controllers\Api\User\ProfileController as UserProfileController;
use App\Http\Controllers\Api\User\StartUpController as UserStartUpController;
use App\Http\Controllers\Api\User\WishlistController as UserWishlistController;
use App\Http\Controllers\Api\Admin\ResponseController as AdminResponseController;
use App\Http\Controllers\Api\Startup\ProductController as StartupProductController;
use App\Http\Controllers\Api\Startup\ProfileController as StartupProfileController;
use App\Http\Controllers\Api\Factory\ResponseController as FactoryResponseController;
use App\Http\Controllers\Api\Factory\ProfileController as FactoryProfileController;
use App\Http\Controllers\Api\Startup\ResponseController as StartupResponseController;
use App\Http\Controllers\Api\Factory\StartupRequestController as FactoryStartupRequestController;
use App\Http\Controllers\Api\StartUp\PaymentController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Api\StartUp\OrderController as StartUpOrderController;

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
Route::get('auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


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

        Route::resource('deals', AdminDealController::class)->only(['index', 'show']);
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

        Route::post('response/send-offer/{request_id}', [FactoryResponseController::class, 'sendOffer']);

        Route::resource('deals', FactoryDealController::class)->only(['index', 'show']);
        Route::post('deals/{id}/order-done', [FactoryDealController::class, 'orderDone']);

        Route::get('profile', [FactoryProfileController::class, 'index']);
        Route::put('profile', [FactoryProfileController::class, 'update']);
        Route::delete('profile', [FactoryProfileController::class, 'destroy']);
    });
});

//                                       startup routes
//                                       startup login
Route::controller(StartupAuthController::class)->group(function () {
    Route::post('startup/login', 'login');
});
Route::middleware('auth:startup')->group(function () {
    Route::prefix('startup')->group(function () {
        Route::resource('request', RequestController::class)->only(['index', 'show', 'destroy', 'store']);

        // sizes
        Route::get('/sizes', [SizeController::class, 'index']);
        Route::put('/sizes/{id}', [SizeController::class, 'update']);
        Route::post('/sizes', [SizeController::class, 'store']);
        Route::delete('/sizes/{id}', [SizeController::class, 'destroy']);

        // products
        // Route::post('/products', [StartupProductController::class, 'store']);
        Route::resource('/products', StartupProductController::class)->except(['create', 'edit']);

        //profile
        Route::controller(StartupProfileController::class)->group(function () {
            Route::get('/profile', 'index');
            Route::put('/profile', 'update');
            Route::delete('/profile', 'destroy');
        });
        //fctory responses
        Route::resource('factory/response', StartupResponseController::class)->only(['index', 'show']);
        Route::post('/factory-responses/{id}/accept', [StartupResponseController::class, 'acceptFactoryResponse']);
        Route::post('/factory-responses/{id}/reject', [StartupResponseController::class, 'rejectFactoryResponse']);

        Route::resource('deals', StartupDealController::class)->only(['index', 'show']);

        Route::post('deals/{deal}/pay-deposit', [PaymentController::class, 'payDeposit']);
        Route::post('deals/{deal}/pay-final', [PaymentController::class, 'payFinal']);

        Route::post('rate/deal/{id}', [RatingController::class, 'store']);
        
        // count new orders
        // Route::resource('orders', StartUpOrderController::class)->only(['index', 'show']);
        Route::get('/orders/count/new', [StartUpOrderController::class, 'countNewOrders']);
        Route::get('/orders', [StartUpOrderController::class, 'index']);
        Route::get('/orders/{id}', [StartUpOrderController::class, 'show']);

    });
});


//                                       general routes
Route::prefix('general')->group(function () {
    Route::resource('products', UserProductController::class)->only(['index', 'show']);
    Route::get('/best-sellers', [UserProductController::class, 'bestSellers']);
    Route::get('/new_arrivals', [UserProductController::class, 'newArrivals']);
    Route::get('/discounted', [UserProductController::class, 'discountedProducts']);

    Route::resource('subcategory', GeneralSubCategoryController::class)->only(['index', 'show']);
});

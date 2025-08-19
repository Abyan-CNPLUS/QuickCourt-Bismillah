<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FnbController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VenuesController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\FnbOrderController;

use App\Http\Controllers\API\BookingHistoryController;
use App\Http\Controllers\API\Owner\OwnerVenueController;
use App\Http\Controllers\API\VenueAvailableTimeController;
use App\Http\Controllers\API\Owner\OwnerDashboardController;
use App\Http\Controllers\API\Admin\BookingController as AdminBookingController;


Route::post('/firebase-login', [AuthController::class, 'firebaseLogin']);
Route::post('/firebase-register', [AuthController::class, 'firebaseRegister']);

Route::apiResource('venues', VenuesController::class);
Route::get('/facilities', [VenuesController::class, 'getFacilities']);

Route::get('/venues/{id}/available-times', [VenueAvailableTimeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::prefix('fnb')->group(function () {
    Route::get('/cities', [FnbController::class, 'getCities']);
    Route::get('/venues/{city_id}', [FnbController::class, 'getVenuesByCity']);
    Route::get('/categories/venue/{id}', [FnbController::class, 'getFnbCategoriesByVenue']);
    Route::get('/menu/venue/{venueId}/category/{categoryId}', [FnbController::class, 'getFnbMenusByCategoryAndVenue']);
    Route::get('/menu/venue/{venueId}', [FnbController::class, 'getFnbMenuByVenue']);
});

    Route::get('/fnb-orders', [FnbOrderController::class, 'index']);
    Route::post('/fnb-orders', [FnbOrderController::class, 'store']);
    Route::get('/fnb-orders/{id}', [FnbOrderController::class, 'show']);



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json([
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'role' => $request->user()->role,
        ]);
    });

    Route::apiResource('bookings', BookingController::class);

    Route::apiResource('users', UserController::class);

    Route::get('/owner/venues', [OwnerVenueController::class, 'index']);
    Route::post('/owner/venues', [OwnerVenueController::class, 'store']);
    Route::put('/owner/venues/{venue}', [OwnerVenueController::class, 'update']);
    Route::delete('/owner/venues/{venue}', [OwnerVenueController::class, 'destroy']);
    Route::get('/owner/check-venue', [OwnerVenueController::class, 'checkVenueStatus']);

    Route::get('/cart-items', [CartController::class, 'index']);
    Route::post('/add-cart', [CartController::class, 'store']);
    Route::put('/fnb-cart/{id}', [CartController::class, 'update']);
    Route::delete('/fnb-cart/{id}', [CartController::class, 'destroy']);
    Route::post('/fnb-cart/checkout', [CartController::class, 'checkout']);

    Route::get('/booking-history', [BookingHistoryController::class, 'index']);
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index']);
});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('admin-booking', AdminBookingController::class);
});



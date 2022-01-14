<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\EventApiController;
use App\Http\Controllers\Api\v1\PromoCodeApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(["prefix" => "/v1"], function () {
    Route::post("/login", [AuthController::class, "login"])->name("login");

    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/promo-codes/all", [PromoCodeApiController::class, "all"]);
        Route::get("/promo-codes/active", [PromoCodeApiController::class, "active"]);
        Route::post("/promo-codes", [PromoCodeApiController::class, "store"]);
        Route::post('/promo-codes/redeem', [PromoCodeApiController::class, 'redeem']);
        Route::post("/events", [EventApiController::class, "store"]);
        Route::put("/promo-codes/{promo_code}", [PromoCodeApiController::class, "update"]); // deactivate

        // Route::get("/promo-codes/{promo_code}", [PromoCodeApiController::class, "show"]);
        // Route::post("/events/{event}", [EventApiController::class, "update"]);
    });
});

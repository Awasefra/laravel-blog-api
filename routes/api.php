<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::get("health", function () {
    return response()->json([
        "code" => 200,
        "message" => "Hello, Universe!",
        "data" => "Halo dunia, selamat datang di service BackEnd KPI. Enjoy your journey on our fullsite. Here you only find this boring string"
    ]);
});

Route::middleware(["forceJson"])->group(function () {


    Route::prefix("auth")->controller(AuthController::class)->group(function () {
        Route::post("login", "login");

        Route::middleware(["api", "auth:api"])->group(function () {
            Route::get("me", "me");
            Route::post("change-password", "changePassword");
            Route::post("logout", "logout");
        });
    });

    Route::prefix('posts')->controller(PostController::class)->group(function () {
        Route::get('/', 'index');

        Route::apiResource('/', PostController::class)
            ->middleware('auth:api')
            ->parameters(['' => 'id'])
            ->except(['index']);
    });
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::withoutMiddleware('auth:sanctum')->group(function () {
        Route::post('users/login', [UsersController::class, 'login']);
        Route::post('users/superadmin', [UsersController::class, 'superadmin']);
    });
    Route::resource('users', UsersController::class)->except(['login', 'update', 'superadmin']);
    Route::post('users/{id}', [UsersController::class, 'update']); 
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\AccessController;
use Modules\Company\Http\Controllers\CompanyController;
use Modules\Company\Http\Controllers\ProjectController;
use Modules\Company\Http\Controllers\RolesController;

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
        Route::post('company', [CompanyController::class, 'store']);
    });
    Route::resource('roles', RolesController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('company', CompanyController::class)->except(['store']);
});

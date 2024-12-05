<?php

use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\AccessController;
use Modules\Company\Http\Controllers\CompanyController;
use Modules\Company\Http\Controllers\ProjectController;
use Modules\Company\Http\Controllers\RolesController;
use Modules\Company\Http\Controllers\UnitController;
use Modules\Company\Http\Controllers\UnitStatusController;
use Modules\Company\Http\Controllers\UnitTypeController;

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
    Route::resource('unitstatus', UnitStatusController::class)->except(['update']);
    Route::resource('unittype', UnitTypeController::class)->except(['update']);
    Route::resource('unit', UnitController::class)->except(['update']);
    Route::resource('project', ProjectController::class)->except(['update']);
    Route::resource('company', CompanyController::class)->except(['store']);
    Route::post('unit/{id}', [UnitController::class, 'update']);
    Route::post('unittype/{id}', [UnitTypeController::class, 'update']);
    Route::post('unitstatus/{id}', [UnitStatusController::class, 'update']);
    Route::post('project/{id}', [ProjectController::class, 'update']);
    Route::put('project/{id}', [ProjectController::class, 'changeStatus']);
});

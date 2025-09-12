<?php

declare(strict_types=1);

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\EndpointController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthenticatedSessionController::class, 'createToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::controller(CompanyController::class)->group(function () {
        Route::post('company', 'store');
        Route::get('company', 'get');
        Route::put('company', 'update');
        Route::delete('company', 'destroy');
    });

    Route::controller(ProjectController::class)->group(function () {
        Route::post('project', 'store');
        Route::get('project', 'get');
        Route::put('project', 'update');
        Route::delete('project', 'destroy');
    });

    Route::controller(ApplicationController::class)->group(function () {
        Route::post('application', 'store');
        Route::get('application', 'get');
        Route::put('application', 'update');
        Route::delete('application', 'destroy');
    });

    Route::controller(ScreenController::class)->group(function () {
        Route::post('screen', 'store');
        Route::get('screen', 'get');
        Route::put('screen', 'update');
        Route::delete('screen', 'destroy');
    });

    Route::controller(EndpointController::class)->group(function () {
        Route::post('endpoint', 'store');
        Route::get('endpoint', 'get');
        Route::put('endpoint', 'update');
        Route::delete('endpoint', 'destroy');
    });

    Route::controller(DatabaseController::class)->group(function () {
        Route::post('database', 'store');
        Route::get('database', 'get');
        Route::put('database', 'update');
        Route::delete('database', 'destroy');
    });

    Route::controller(TableController::class)->group(function () {
        Route::post('table', 'store');
        Route::get('table', 'get');
        Route::put('table', 'update');
        Route::delete('table', 'destroy');
    });

    Route::controller(ColumnController::class)->group(function () {
        Route::post('column', 'store');
        Route::get('column', 'get');
        Route::put('column', 'update');
    });
});

<?php

declare(strict_types=1);

use App\Http\Controllers\ViewResources\ColumnController;
use App\Http\Controllers\ViewResources\EndpointController;
use App\Http\Controllers\ViewResources\TableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewResources\ScreenController;
use App\Http\Controllers\ViewResources\CompanyController;
use App\Http\Controllers\ViewResources\ProjectController;
use App\Http\Controllers\ViewResources\DatabaseController;
use App\Http\Controllers\ViewResources\ApplicationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('company', CompanyController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('database', DatabaseController::class);
    Route::resource('application', ApplicationController::class);
    Route::resource('screen', ScreenController::class);
    Route::resource('endpoint', EndpointController::class);
    Route::resource('table', TableController::class);
    Route::resource('column', ColumnController::class);
});

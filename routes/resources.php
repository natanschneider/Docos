<?php

declare(strict_types=1);

use App\Http\Controllers\ImportDatabaseController;
use App\Http\Controllers\ViewResources\ApplicationController;
use App\Http\Controllers\ViewResources\ColumnController;
use App\Http\Controllers\ViewResources\CompanyController;
use App\Http\Controllers\ViewResources\DatabaseController;
use App\Http\Controllers\ViewResources\EndpointController;
use App\Http\Controllers\ViewResources\ProjectController;
use App\Http\Controllers\ViewResources\ScreenController;
use App\Http\Controllers\ViewResources\TableController;
use App\Http\Controllers\ViewResources\UserCompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('company', CompanyController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('database', DatabaseController::class);
    Route::resource('application', ApplicationController::class);
    Route::resource('screen', ScreenController::class);
    Route::resource('endpoint', EndpointController::class);
    Route::resource('table', TableController::class);
    Route::resource('column', ColumnController::class);
    Route::resource('users', UserCompanyController::class);

    Route::get('import', [ImportDatabaseController::class, 'index'])->name('import-db');
    Route::post('import', [ImportDatabaseController::class, 'store'])->name('import-db.store');
});

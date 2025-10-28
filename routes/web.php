<?php

declare(strict_types=1);

use App\Http\Controllers\ViewResources\CompanyController;
use App\Http\Controllers\ViewResources\ProjectController;
use App\Repositories\ViewsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function (Request $request) {
        return Inertia::render('dashboard', [
            'categories' => (new ViewsRepository())->dashboard($request),
        ]);
    })->name('dashboard');

    Route::resource('company', CompanyController::class);

    Route::get('change-company/{company}', function ($company) {
        return Redirect::route('dashboard')->withCookie('currentCompany', $company);
    })->name('change-company');

    Route::resource('project', ProjectController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

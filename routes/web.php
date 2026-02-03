<?php

declare(strict_types=1);

use App\Http\Controllers\ViewResources\ApplicationController;
use App\Http\Controllers\ViewResources\CompanyController;
use App\Http\Controllers\ViewResources\DatabaseController;
use App\Http\Controllers\ViewResources\ProjectController;
use App\Repositories\ProjectRepository;
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
    Route::resource('project', ProjectController::class);
    Route::resource('database', DatabaseController::class);
    Route::resource('application', ApplicationController::class);

    Route::get('change-company/{company}', function (string $company, Request $request) {
        $request->cookies->set('currentCompany', $company);
        $currentProject = (new ProjectRepository())->getLatest($request);
        $currentProject = (is_array($currentProject) && isset($currentProject['id'])) ? $currentProject['id'] : null;

        return Redirect::route('dashboard')
            ->withCookie('currentCompany', $company)
            ->withCookie('currentProject', $currentProject);
    })->name('change-company');

    Route::get('change-project/{project}', function (string $project, Request $request) {
        $request->cookies->set('currentProject', $project);
        $currentProject = (new ProjectRepository())->getLatest($request, (int) $project);
        $currentProject = (is_array($currentProject) && isset($currentProject['id'])) ? $currentProject['id'] : null;

        return Redirect::route('application.index')->withCookie('currentProject', $currentProject);
    })->name('change-project');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

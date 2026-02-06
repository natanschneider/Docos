<?php

declare(strict_types=1);

use App\Repositories\ApplicationRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Repositories\ViewsRepository;
use Illuminate\Support\Facades\Route;
use App\Repositories\ProjectRepository;
use App\Http\Controllers\ViewResources\ScreenController;
use App\Http\Controllers\ViewResources\CompanyController;
use App\Http\Controllers\ViewResources\ProjectController;
use App\Http\Controllers\ViewResources\DatabaseController;
use App\Http\Controllers\ViewResources\ApplicationController;

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
    Route::resource('screen', ScreenController::class);

    Route::get('change-company/{company}', function (string $company, Request $request) {
        $request->cookies->set('currentCompany', $company);
        $currentProject = (new ProjectRepository())->getLatest($request);
        $currentProject = (is_array($currentProject) && isset($currentProject['id'])) ? $currentProject['id'] : null;
        $currentApplication = (new ApplicationRepository())->getLatest($request);
        $currentApplication = (is_array($currentApplication) && isset($currentApplication['id'])) ? $currentApplication['id'] : null;

        $request->cookies->set('currentProject', $currentProject);
        $request->cookies->set('currentApplication', $currentApplication);

        return Redirect::route('dashboard')
            ->withCookie('currentCompany', $company)
            ->withCookie('currentProject', $currentProject)
            ->withCookie('currentApplication', $currentApplication);
    })->name('change-company');

    Route::get('change-project/{project}', function (string $project, Request $request) {
        $request->cookies->set('currentProject', $project);
        $currentProject = (new ProjectRepository())->getLatest($request, (int) $project);
        $currentProject = (is_array($currentProject) && isset($currentProject['id'])) ? $currentProject['id'] : null;

        $currentApplication = (new ApplicationRepository())->getLatest($request);
        $currentApplication = (is_array($currentApplication) && isset($currentApplication['id'])) ? $currentApplication['id'] : null;

        $request->cookies->set('currentProject', $currentProject);
        $request->cookies->set('currentApplication', $currentApplication);

        return Redirect::route('application.index')
            ->withCookie('currentProject', $currentProject)
            ->withCookie('currentApplication', $currentApplication);
    })->name('change-project');

    Route::get('change-application/{project}/{application}', function (string $project, string $application, Request $request) {
        $request->cookies->set('currentProject', $project);
        $request->cookies->set('currentApplication', $application);

        $currentProject = (new ProjectRepository())->getLatest($request, (int) $project);
        $currentProject = (is_array($currentProject) && isset($currentProject['id'])) ? $currentProject['id'] : null;

        $currentApplication = (new ApplicationRepository())->getLatest($request, (int) $application);
        $currentApplication = (is_array($currentApplication) && isset($currentApplication['id'])) ? $currentApplication['id'] : null;

        $request->cookies->set('currentProject', $currentProject);
        $request->cookies->set('currentApplication', $currentApplication);

        return Redirect::route('screen.index')
            ->withCookie('currentApplication', $currentApplication)
            ->withCookie('currentProject', $currentProject);
    })->name('change-application');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

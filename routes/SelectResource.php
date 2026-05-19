<?php

declare(strict_types=1);

use App\Repositories\ApplicationRepository;
use App\Repositories\DatabaseRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TableRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
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

    Route::get(
        'change-application/{location}/{project}/{application}',
        function (string $location, string $project, string $application, Request $request) {
            if (! in_array($location, ['screen', 'endpoint'])) {
                throw new Exception('Invalid location');
            }

            $location = $location === 'screen' ? 'screen.index' : ($location === 'endpoint' ? 'endpoint.index' : 'dashboard');

            $request->cookies->set('currentProject', $project);
            $request->cookies->set('currentApplication', $application);

            $currentProject = (new ProjectRepository())->getLatest($request, (int) $project);
            $currentProject = (is_array($currentProject) && isset($currentProject['id'])) ? $currentProject['id'] : null;

            $currentApplication = (new ApplicationRepository())->getLatest($request, (int) $application);
            $currentApplication = (is_array($currentApplication) && isset($currentApplication['id'])) ? $currentApplication['id'] : null;

            $request->cookies->set('currentProject', $currentProject);
            $request->cookies->set('currentApplication', $currentApplication);

            return Redirect::route($location)
                ->withCookie('currentApplication', $currentApplication)
                ->withCookie('currentProject', $currentProject);
        }
    )->name('change-application');

    Route::get('change-database/{database}', function (string $database, Request $request) {
        $request->cookies->set('currentDatabase', $database);
        $currentDatabase = (new DatabaseRepository())->getLatest($request, (int) $database);
        $currentDatabase = (is_array($currentDatabase) && isset($currentDatabase['id'])) ? $currentDatabase['id'] : null;

        $currentTable = (new TableRepository())->getLatest($request);
        $currentTable = (is_array($currentTable) && isset($currentTable['id'])) ? $currentTable['id'] : null;

        $request->cookies->set('currentDatabase', $currentDatabase);
        $request->cookies->set('currentTable', $currentTable);

        return Redirect::route('table.index')
            ->withCookie('currentDatabase', $currentDatabase)
            ->withCookie('currentTable', $currentTable);
    })->name('change-database');

    Route::get('change-table/{database}/{table}', function (string $database, string $table, Request $request) {
        $request->cookies->set('currentDatabase', $database);
        $request->cookies->set('currentTable', $table);

        $currentDatabase = (new DatabaseRepository())->getLatest($request, (int) $database);
        $currentDatabase = (is_array($currentDatabase) && isset($currentDatabase['id'])) ? $currentDatabase['id'] : null;

        $currentTable = (new TableRepository())->getLatest($request, (int) $table);
        $currentTable = (is_array($currentTable) && isset($currentTable['id'])) ? $currentTable['id'] : null;

        $request->cookies->set('currentDatabase', $currentDatabase);
        $request->cookies->set('currentTable', $currentTable);

        return Redirect::route('column.index')
            ->withCookie('currentDatabase', $currentDatabase)
            ->withCookie('currentTable', $currentTable);
    })->name('change-table');
});

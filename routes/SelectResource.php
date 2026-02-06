<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\ApplicationRepository;

Route::middleware(['auth', 'verified'])->group(function () {
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
});

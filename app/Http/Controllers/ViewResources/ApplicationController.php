<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\ApplicationController as Application;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ProjectController;
use App\Http\Requests\ApplicationRequest;
use App\Http\Requests\DatabaseRequest;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ApplicationRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $request->merge(['project_id' => $currentProject]);
        $applications = (new Application())->get($request);

        return Inertia::render('resources/application/list', [
            'applications' => $applications,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ApplicationRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $request->cookie('currentProject')
        ]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        return Inertia::render('resources/application/manipulate', [
            'application' => null,
            'databases' => $databases,
            'projects' => $projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApplicationRequest $request): RedirectResponse
    {
        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $request->merge(['project_id' => $currentProject]);
        $application = (new Application)->store($request);

        return redirect()->route('application.edit', $application['id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ApplicationRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $currentProject
        ]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $request->merge(['id' => $id, 'project_id' => $currentProject]);
        $application = (new Application)->get($request);

        return Inertia::render('resources/application/manipulate', [
            'application' => Inertia::always($application[0]),
            'projects' => $projects,
            'databases' => $databases,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ApplicationRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $currentProject
        ]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $request->merge(['id' => $id, 'project_id' => $currentProject]);
        $application = (new Application)->get($request);

        return Inertia::render('resources/application/manipulate', [
            'application' => $application,
            'projects' => $projects,
            'databases' => $databases,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, ApplicationRequest $request): RedirectResponse
    {
        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'project_id' => $currentProject]);
        (new Application)->update($request);

        return redirect()->route('application.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ApplicationRequest $request): JsonResponse
    {
        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'project_id' => $currentProject]);
        $response = (new Application)->destroy($request);

        return response()->json($response);
    }
}

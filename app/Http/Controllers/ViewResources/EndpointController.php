<?php

namespace App\Http\Controllers\ViewResources;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Repositories\TableRepository;
use App\Http\Requests\EndpointRequest;
use App\Http\Requests\ApplicationRequest;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EndpointController as Endpoint;

class EndpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EndpointRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $applicationRequest = ApplicationRequest::createFrom($request);
        $applicationRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $currentProject
        ]);
        $applications = (new ApplicationController())->get($applicationRequest);

        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        $endpoints = (new Endpoint())->get($request);

        return Inertia::render('resources/endpoint/list', [
            'endpoints' => $endpoints,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EndpointRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $applicationRequest = ApplicationRequest::createFrom($request);
        $applicationRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $currentProject
        ]);
        $applications = (new ApplicationController())->get($applicationRequest);

        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        $tables = (new TableRepository())->getWithColumns($request);

        return Inertia::render('resources/endpoint/manipulate', [
            'endpoint' => null,
            'tables' => $tables,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EndpointRequest $request): RedirectResponse
    {
        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        (new Endpoint())->store($request);

        return redirect()->route('endpoint.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, EndpointRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $applicationRequest = ApplicationRequest::createFrom($request);
        $applicationRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $currentProject
        ]);
        $applications = (new ApplicationController())->get($applicationRequest);

        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        $tables = (new TableRepository())->getWithColumns($request);

        $request->merge(['id' => $id, 'application_id' => $currentApplication]);
        $endpoint = (new Endpoint())->get($request);

        return Inertia::render('resources/endpoint/manipulate', [
            'endpoint' => $endpoint,
            'tables' => $tables,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, EndpointRequest $request): Response
    {
        $projectRequest = ProjectRequest::createFrom($request);
        $projectRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new ProjectController())->get($projectRequest);

        $currentProject = ($request->hasCookie('currentProject') && $request->cookie('currentProject'))
            ? $request->cookie('currentProject')
            : (new ProjectController())->getLatest($request)['id'];

        $applicationRequest = ApplicationRequest::createFrom($request);
        $applicationRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $currentProject
        ]);
        $applications = (new ApplicationController())->get($applicationRequest);

        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        $tables = (new TableRepository())->getWithColumns($request);

        $request->merge(['id' => $id, 'application_id' => $currentApplication]);
        $endpoint = (new Endpoint())->get($request);

        return Inertia::render('resources/endpoint/manipulate', [
            'endpoint' => $endpoint,
            'tables' => $tables,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EndpointRequest $request, string $id): RedirectResponse
    {
        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'application_id' => $currentApplication]);
        (new Endpoint())->update($request);

        return redirect()->route('endpoint.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, EndpointRequest $request): JsonResponse
    {
        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'application_id' => $currentApplication]);
        $response = (new Endpoint())->destroy($request);

        return response()->json($response);
    }
}

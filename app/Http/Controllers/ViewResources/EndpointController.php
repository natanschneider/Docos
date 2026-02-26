<?php

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\FileController;
use App\Http\Requests\FileRequest;
use App\Repositories\Files\FileRepository;
use App\Repositories\Files\HandleStringToFile;
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
            'doc' => null,
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
        $endpoint = (new Endpoint())->store($request);

        if ($request->has('markdown') && is_string($request->markdown) && strlen($request->markdown) > 0) {
            $request->merge(['id' => $endpoint->id]);
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeEndpoint($fileRequest);
        }

        return redirect()->route('endpoint.edit', $endpoint->id);
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

        $doc = null;
        if ($endpoint[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $endpoint[0]->doc_file);
        }

        return Inertia::render('resources/endpoint/manipulate', [
            'endpoint' => $endpoint,
            'doc' => $doc,
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

        $doc = null;
        if ($endpoint[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $endpoint[0]->doc_file);
        }

        return Inertia::render('resources/endpoint/manipulate', [
            'endpoint' => $endpoint,
            'doc' => $doc,
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

        if ($request->has('markdown') && is_string($request->markdown) && strlen($request->markdown) > 0) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeEndpoint($fileRequest);
        }

        return redirect()->route('endpoint.edit', $id);
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

        $fileRequest = FileRequest::createFrom($request);
        (new FileController())->deleteEndpoint($fileRequest);

        return response()->json($response);
    }
}

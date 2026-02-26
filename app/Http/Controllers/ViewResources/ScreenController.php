<?php

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\FileController;
use App\Http\Requests\FileRequest;
use App\Repositories\Files\FileRepository;
use App\Repositories\Files\HandleStringToFile;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScreenRequest;
use App\Http\Requests\ProjectRequest;
use App\Repositories\TableRepository;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ApplicationRequest;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ScreenController as Screen;

class ScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ScreenRequest $request): Response
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
        $screens = (new Screen())->get($request);

        return Inertia::render('resources/screen/list', [
            'screens' => $screens,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ScreenRequest $request): Response
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

        return Inertia::render('resources/screen/manipulate', [
            'screen' => null,
            'doc' => null,
            'tables' => $tables,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScreenRequest $request): RedirectResponse
    {
        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        $screen = (new Screen())->store($request);

        if ($request->has('markdown')) {
            $request->merge(['id' => $screen->id]);
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeScreen($fileRequest);
        }

        return redirect()->route('screen.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ScreenRequest $request): Response
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
        $screen = (new Screen())->get($request);

        $doc = null;
        if ($screen[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $screen[0]->doc_file);
        }

        return Inertia::render('resources/screen/manipulate', [
            'screen' => $screen,
            'doc' => $doc,
            'tables' => $tables,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ScreenRequest $request): Response
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
        $screen = (new Screen())->get($request);

        $doc = null;
        if ($screen[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $screen[0]->doc_file);
        }

        return Inertia::render('resources/screen/manipulate', [
            'screen' => $screen,
            'doc' => $doc,
            'tables' => $tables,
            'projects' => $projects,
            'applications' => $applications,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScreenRequest $request, string $id): RedirectResponse
    {
        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'application_id' => $currentApplication]);
        (new Screen())->update($request);

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeScreen($fileRequest);
        }

        return redirect()->route('screen.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ScreenRequest $request): JsonResponse
    {
        $currentApplication = ($request->hasCookie('currentApplication') && $request->cookie('currentApplication'))
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'application_id' => $currentApplication]);
        $response = (new Screen())->destroy($request);

        $fileRequest = FileRequest::createFrom($request);
        (new FileController())->deleteScreen($fileRequest);

        return response()->json($response);
    }
}

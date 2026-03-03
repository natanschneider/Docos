<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectController as Project;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectRequest $request): Response
    {
        $request->merge(['company_id' => $request->cookie('currentCompany')]);
        $projects = (new Project)->get($request);

        return Inertia::render('resources/project/list', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('resources/project/manipulate', [
            'project' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): RedirectResponse
    {
        $request->merge(['company_id' => $request->cookie('currentCompany')]);
        $project = (new Project)->store($request);

        return redirect()->route('project.edit', $project['id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ProjectRequest $request): Response
    {
        $request->merge(['id' => $id]);
        $project = (new Project)->get($request)->load(['applications', 'company']);

        $currentCompany = (isset($project[0]) && $project[0]->company_id)
            ? $project[0]->company_id
            : $request->cookie('currentCompany');

        Cookie::queue('currentCompany', $currentCompany, 60);
        $request->cookies->set('currentCompany', $currentCompany);

        return Inertia::render('resources/project/view', [
            'project' => $project,
        ])->with('currentCompany', $currentCompany);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ProjectRequest $request): Response
    {
        $request->merge(['id' => $id]);
        $project = (new Project)->get($request);

        $currentCompany = (isset($project[0]) && $project[0]->company_id)
            ? $project[0]->company_id
            : $request->cookie('currentCompany');

        Cookie::queue('currentCompany', $currentCompany, 60);
        $request->cookies->set('currentCompany', $currentCompany);

        return Inertia::render('resources/project/manipulate', [
            'project' => Inertia::always($project[0]),
        ])->with('currentCompany', $currentCompany);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, ProjectRequest $request): RedirectResponse
    {
        $request->merge(['id' => $id, 'company_id' => $request->cookie('currentCompany')]);
        (new Project)->update($request);

        return redirect()->route('project.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ProjectRequest $request): JsonResponse
    {
        $request->merge(['id' => $id, 'company_id' => $request->cookie('currentCompany')]);
        $response = (new Project)->destroy($request);

        return response()->json($response);
    }
}

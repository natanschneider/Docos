<?php

namespace App\Http\Controllers\ViewResources;

use App\Http\Requests\ApplicationRequest;
use App\Repositories\TableRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ScreenController as Screen;
use App\Http\Requests\ScreenRequest;
use App\Http\Controllers\ApplicationController;
use Inertia\Inertia;
use Inertia\Response;

class ScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ScreenRequest $request)
    {
        $applicationRequest = ApplicationRequest::createFrom($request);
        $applicationRequest->mergeIfMissing([
            'company_id' => $request->cookie('currentCompany'),
            'project_id' => $request->cookie('currentProject')
        ]);
        $applications = (new ApplicationController())->get($applicationRequest);

        $currentApplication = $request->hasCookie('currentApplication')
            ? $request->cookie('currentApplication')
            : (new ApplicationController())->getLatest($request)['id'];

        $request->merge(['application_id' => $currentApplication]);
        $screens = (new Screen())->get($request);

        return Inertia::render('resources/screen/list', [
            'screens' => $screens,
            'applications' => $applications,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ScreenRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

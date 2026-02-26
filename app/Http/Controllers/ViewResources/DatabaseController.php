<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController as Database;
use App\Http\Requests\DatabaseRequest;
use App\Models\Descriptions\Engine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DatabaseRequest $request): Response
    {
        $request->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new Database)->get($request);

        return Inertia::render('resources/database/list', [
            'databases' => $databases,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('resources/database/manipulate', [
            'database' => null,
            'engines' => Engine::all(['id', 'name'])->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DatabaseRequest $request): RedirectResponse
    {
        $request->merge(['company_id' => $request->cookie('currentCompany')]);
        $database = (new Database)->store($request);

        return redirect()->route('database.edit', $database['id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, DatabaseRequest $request): Response
    {
        $request->merge(['id' => $id, 'company_id' => $request->cookie('currentCompany')]);
        $database = (new Database)->get($request);

        return Inertia::render('resources/database/manipulate', [
            'database' => $database,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, DatabaseRequest $request): Response
    {
        $request->merge(['id' => $id, 'company_id' => $request->cookie('currentCompany')]);
        $database = (new Database)->get($request);

        return Inertia::render('resources/database/manipulate', [
            'database' => Inertia::always($database[0]),
            'engines' => Engine::all(['id', 'name'])->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, DatabaseRequest $request): RedirectResponse
    {
        $request->merge(['id' => $id, 'company_id' => $request->cookie('currentCompany')]);
        (new Database)->update($request);

        return redirect()->route('database.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DatabaseRequest $request): JsonResponse
    {
        $request->merge(['id' => $id, 'company_id' => $request->cookie('currentCompany')]);
        $response = (new Database)->destroy($request);

        return response()->json($response);
    }
}

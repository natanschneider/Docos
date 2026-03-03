<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController as Database;
use App\Http\Requests\DatabaseRequest;
use App\Http\Requests\TableRequest;
use App\Models\Descriptions\Engine;
use App\Repositories\TableRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
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
        $request->cookies->set('currentDatabase', $id);
        Cookie::queue('currentDatabase', $id, 60);
        $tableRequest = TableRequest::createFrom($request);

        $request->merge(['id' => $id]);
        $database = (new Database)->get($request)->load('company');

        $currentCompany = (isset($database[0]) && $database[0]->company_id)
            ? $database[0]->company_id
            : $request->cookie('currentCompany');

        Cookie::queue('currentCompany', $currentCompany, 60);
        $request->cookies->set('currentCompany', $currentCompany);
        $tableRequest->cookies->set('currentCompany', $currentCompany);

        $tableRequest->merge([
            'company_id' => $currentCompany,
            'database_id' => $id
        ]);
        $tables = (new TableRepository())->getWithColumns($tableRequest)->load(['columns.relatedFks', 'columns.relatedPks', 'database', 'columns.type', 'columns.constraints']);

        return Inertia::render('resources/database/view', [
            'database' => $database,
            'tables' => $tables,
        ])->with([
            'currentDatabase' => $id,
            'currentCompany' => $currentCompany
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, DatabaseRequest $request): Response
    {
        $request->cookies->set('currentDatabase', $id);
        Cookie::queue('currentDatabase', $id, 60);
        $request->merge(['id' => $id]);
        $database = (new Database)->get($request);

        $currentCompany = (isset($database[0]) && $database[0]->company_id)
            ? $database[0]->company_id
            : $request->cookie('currentCompany');

        Cookie::queue('currentCompany', $currentCompany, 60);
        $request->cookies->set('currentCompany', $currentCompany);

        return Inertia::render('resources/database/manipulate', [
            'database' => Inertia::always($database[0]),
            'engines' => Engine::all(['id', 'name'])->toArray(),
        ])->with([
            'currentDatabase' => $id,
            'currentCompany' => $currentCompany
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

<?php

namespace App\Http\Controllers\ViewResources;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TableRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DatabaseRequest;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\TableController as Table;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TableRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $request->merge(['database_id' => $currentDatabase]);
        $tables = (new Table())->get($request);

        return Inertia::render('resources/table/list', [
            'tables' => $tables,
            'databases' => $databases
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TableRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        return Inertia::render('resources/table/manipulate', [
            'table' => null,
            'databases' => $databases
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TableRequest $request): RedirectResponse
    {
        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $request->merge(['database_id' => $currentDatabase]);
        (new Table())->store($request);

        return redirect()->route('table.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TableRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'database_id' => $currentDatabase]);
        $table = (new Table())->get($request);

        return Inertia::render('resources/table/manipulate', [
            'table' => Inertia::always($table[0]),
            'databases' => $databases
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, TableRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'database_id' => $currentDatabase]);
        $table = (new Table())->get($request);

        return Inertia::render('resources/table/manipulate', [
            'table' => $table,
            'databases' => $databases
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, TableRequest $request): RedirectResponse
    {
        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'database_id' => $currentDatabase]);
        (new Table())->update($request);

        return redirect()->route('table.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TableRequest $request): JsonResponse
    {
        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'database_id' => $currentDatabase]);
        $response = (new Table())->destroy($request);

        return response()->json($response);
    }
}

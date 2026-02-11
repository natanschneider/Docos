<?php

namespace App\Http\Controllers\ViewResources;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TableRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColumnRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DatabaseRequest;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ColumnController as Column;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ColumnRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $tableRequest = TableRequest::createFrom($request);
        $tableRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase
        ]);
        $tables = (new TableController())->get($tableRequest);

        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge(['table_id' => $currentTable]);
        $columns = (new Column())->get($request);

        return Inertia::render('resources/column/list', [
            'columns' => $columns,
            'tables' => $tables,
            'databases' => $databases
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ColumnRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $tableRequest = TableRequest::createFrom($request);
        $tableRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase
        ]);
        $tables = (new TableController())->get($tableRequest);

        return Inertia::render('resources/column/manipulate', [
            'column' => null,
            'tables' => $tables,
            'databases' => $databases
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColumnRequest $request): RedirectResponse
    {
        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge(['table_id' => $currentTable]);
        (new Column())->store($request);

        return redirect()->route('column.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ColumnRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $tableRequest = TableRequest::createFrom($request);
        $tableRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase
        ]);
        $tables = (new TableController())->get($tableRequest);

        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge([
            'id' => $id,
            'database_id' => $currentDatabase,
            'table_id' => $currentTable
        ]);
        $column = (new Column())->get($request);

        return Inertia::render('resources/column/manipulate', [
            'column' => $column,
            'tables' => $tables,
            'databases' => $databases
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ColumnRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase'))
            ? $request->cookie('currentDatabase')
            : (new DatabaseController())->getLatest($request)['id'];

        $tableRequest = TableRequest::createFrom($request);
        $tableRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase
        ]);
        $tables = (new TableController())->get($tableRequest);

        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge([
            'id' => $id,
            'database_id' => $currentDatabase,
            'table_id' => $currentTable
        ]);
        $column = (new Column())->get($request);

        return Inertia::render('resources/column/manipulate', [
            'column' => $column,
            'tables' => $tables,
            'databases' => $databases
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColumnRequest $request, string $id): RedirectResponse
    {
        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'table_id' => $currentTable]);
        (new Column())->update($request);

        return redirect()->route('column.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ColumnRequest $request): JsonResponse
    {
        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge(['id' => $id, 'table_id' => $currentTable]);
        $response = (new Column())->destroy($request);

        return response()->json($response);
    }
}

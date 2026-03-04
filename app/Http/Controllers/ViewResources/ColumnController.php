<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\ColumnController as Column;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TableController;
use App\Http\Requests\ColumnRequest;
use App\Http\Requests\DatabaseRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\TableRequest;
use App\Repositories\ColumnRepository;
use App\Repositories\Files\FileRepository;
use App\Repositories\Files\HandleStringToFile;
use App\Repositories\TableRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

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
            'database_id' => $currentDatabase,
        ]);
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge(['table_id' => $currentTable]);
        $columns = (new Column())->get($request);

        return Inertia::render('resources/column/list', [
            'columns' => $columns,
            'tables' => $tables,
            'databases' => $databases,
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
            'database_id' => $currentDatabase,
        ]);
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $columnRequest = ColumnRequest::createFrom($request);
        $columnRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
            'constraint_id' => 1,
        ]);

        $primaryKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $columnRequest->merge(['constraint_id' => 2]);
        $foreignKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $types = (new ColumnRepository())->getTypes();

        $constraints = (new ColumnRepository())->getConstraints();

        return Inertia::render('resources/column/manipulate', [
            'column' => null,
            'doc' => null,
            'tables' => $tables,
            'databases' => $databases,
            'primaryKey' => $primaryKey,
            'foreignKey' => $foreignKey,
            'types' => $types,
            'constraints' => $constraints,
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
        $col = (new Column())->store($request);

        if ($request->has('markdown') && is_string($request->markdown) && mb_strlen($request->markdown) > 0) {
            $request->merge(['id' => $col->id]);
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeColumn($fileRequest);
        }

        return redirect()->route('column.edit', $col->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ColumnRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $tableRequest = TableRequest::createFrom($request);

        $request->merge(['id' => $id]);
        $column = (new Column())->get($request)->load(['table', 'endpoints', 'screens', 'index', 'table.database']);

        $currentCompany = (isset($column[0]) && isset($column[0]?->table) && isset($column[0]?->table?->database) && $column[0]?->table?->database?->company_id)
            ? $column[0]?->table?->database?->company_id
            : $request->cookie('currentCompany');

        $currentDatabase = (isset($column[0]) && isset($column[0]?->table) && $column[0]?->table?->database_id)
            ? $column[0]?->table?->database_id
            : (new DatabaseController())->getLatest($request)['id'];

        Cookie::queue('currentDatabase', $currentDatabase, 60);
        Cookie::queue('currentCompany', $currentCompany, 60);

        $databaseRequest->merge(['company_id' => $currentCompany]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $tableRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
        ]);
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $currentTable = (isset($column[0]) && $column[0]?->table_id)
            ? $column[0]?->table_id
            : (new TableController())->getLatest($request)['id'];

        Cookie::queue('currentTable', $currentTable, 60);

        $types = (new ColumnRepository())->getTypes();
        $constraints = (new ColumnRepository())->getConstraints();

        $doc = null;
        if (isset($column[0]) && $column[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $column[0]->doc_file);
        }

        return Inertia::render('resources/column/view', [
            'column' => $column,
            'doc' => $doc,
            'tables' => $tables,
            'databases' => $databases,
            'types' => $types,
            'constraints' => $constraints,
        ])->with([
            'currentTable' => $currentTable,
            'currentDatabase' => $currentDatabase,
            'currentCompany' => $currentCompany,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ColumnRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $tableRequest = TableRequest::createFrom($request);

        $request->merge(['id' => $id]);
        $column = (new Column())->get($request)->load(['table', 'table.database']);

        $currentCompany = (isset($column[0]) && isset($column[0]?->table) && isset($column[0]?->table?->database) && $column[0]?->table?->database?->company_id)
            ? $column[0]?->table?->database?->company_id
            : $request->cookie('currentCompany');

        $currentDatabase = (isset($column[0]) && isset($column[0]?->table) && $column[0]?->table?->database_id)
            ? $column[0]?->table?->database_id
            : (new DatabaseController())->getLatest($request)['id'];

        Cookie::queue('currentDatabase', $currentDatabase, 60);
        Cookie::queue('currentCompany', $currentCompany, 60);

        $databaseRequest->merge(['company_id' => $currentCompany]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $tableRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
        ]);
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $currentTable = (isset($column[0]) && $column[0]?->table_id)
            ? $column[0]?->table_id
            : (new TableController())->getLatest($request)['id'];

        Cookie::queue('currentTable', $currentTable, 60);

        $columnRequest = ColumnRequest::createFrom($request);
        $columnRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
            'constraint_id' => 1,
        ]);

        $primaryKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $columnRequest->merge(['constraint_id' => 2]);
        $foreignKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $types = (new ColumnRepository())->getTypes();
        $constraints = (new ColumnRepository())->getConstraints();

        $doc = null;
        if (isset($column[0]) && $column[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $column[0]->doc_file);
        }

        return Inertia::render('resources/column/manipulate', [
            'column' => $column,
            'doc' => $doc,
            'tables' => $tables,
            'databases' => $databases,
            'primaryKey' => $primaryKey,
            'foreignKey' => $foreignKey,
            'types' => $types,
            'constraints' => $constraints,
        ])->with([
            'currentTable' => $currentTable,
            'currentDatabase' => $currentDatabase,
            'currentCompany' => $currentCompany,
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

        if ($request->has('markdown') && is_string($request->markdown) && mb_strlen($request->markdown) > 0) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeColumn($fileRequest);
        }

        return redirect()->route('column.edit', $id);
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

        $fileRequest = FileRequest::createFrom($request);
        (new FileController())->deleteColumn($fileRequest);

        return response()->json($response);
    }
}

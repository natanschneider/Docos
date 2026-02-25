<?php

namespace App\Http\Controllers\ViewResources;

use App\Repositories\ColumnRepository;
use App\Repositories\Files\FileRepository;
use App\Repositories\TableRepository;
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
use App\Repositories\Files\HandleStringToFile;
use App\Http\Controllers\FileController;
use App\Http\Requests\FileRequest;

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
        $tables = (new TableRepository())->getWithColumns($tableRequest);

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
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $columnRequest = ColumnRequest::createFrom($request);
        $columnRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
            'constraint_id' => 1
        ]);

        $primaryKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $columnRequest->merge([ 'constraint_id' => 2 ]);
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
            'constraints' => $constraints
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

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeColumn($fileRequest);
        }

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
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge([
            'id' => $id,
            'database_id' => $currentDatabase,
            'table_id' => $currentTable
        ]);
        $column = (new Column())->get($request);

        $columnRequest = ColumnRequest::createFrom($request);
        $columnRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
            'constraint_id' => 1
        ]);

        $primaryKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $columnRequest->merge([ 'constraint_id' => 2 ]);
        $foreignKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $types = (new ColumnRepository())->getTypes();

        $constraints = (new ColumnRepository())->getConstraints();

        $doc = null;
        if ($column[0]->doc_file) {
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
            'constraints' => $constraints
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
        $tables = (new TableRepository())->getWithColumns($tableRequest);

        $currentTable = ($request->hasCookie('currentTable') && $request->cookie('currentTable'))
            ? $request->cookie('currentTable')
            : (new TableController())->getLatest($request)['id'];

        $request->merge([
            'id' => $id,
            'database_id' => $currentDatabase,
            'table_id' => $currentTable
        ]);

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeColumn($fileRequest);
        }

        $column = (new Column())->get($request);

        $columnRequest = ColumnRequest::createFrom($request);
        $columnRequest->merge([
            'company_id' => $request->cookie('currentCompany'),
            'database_id' => $currentDatabase,
            'constraint_id' => 1
        ]);

        $primaryKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $columnRequest->merge([ 'constraint_id' => 2 ]);
        $foreignKey = (new ColumnRepository())->getByConstraint($columnRequest);

        $types = (new ColumnRepository())->getTypes();

        $constraints = (new ColumnRepository())->getConstraints();

        $doc = null;
        if ($column[0]->doc_file) {
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
            'constraints' => $constraints
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

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeColumn($fileRequest);
        }

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

        $fileRequest = FileRequest::createFrom($request);
        (new FileController())->deleteColumn($fileRequest);

        return response()->json($response);
    }
}

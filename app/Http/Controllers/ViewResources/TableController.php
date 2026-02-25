<?php

namespace App\Http\Controllers\ViewResources;

use App\Repositories\Files\FileRepository;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TableRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DatabaseRequest;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\TableController as Table;
use App\Repositories\Files\HandleStringToFile;
use App\Http\Controllers\FileController;

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
            'doc' => null,
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

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeTable($fileRequest);
        }

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

        $doc = (new FileRepository())->get('docs', $table[0]->doc_file);

        return Inertia::render('resources/table/manipulate', [
            'table' => Inertia::always($table[0]),
            'doc' => $doc,
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

        $doc = (new FileRepository())->get('docs', $table[0]->doc_file);

        return Inertia::render('resources/table/manipulate', [
            'table' => $table,
            'doc' => $doc,
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

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeTable($fileRequest);
        }

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

        if ($request->has('markdown')) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeTable($fileRequest);
        }

        return response()->json($response);
    }
}

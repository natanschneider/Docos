<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TableController as Table;
use App\Http\Requests\DatabaseRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\TableRequest;
use App\Repositories\Files\FileRepository;
use App\Repositories\Files\HandleStringToFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

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
            'databases' => $databases,
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
            'databases' => $databases,
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
        $table = (new Table())->store($request);

        if ($request->has('markdown') && is_string($request->markdown) && mb_strlen($request->markdown) > 0) {
            $request->merge(['id' => $table->id]);
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeTable($fileRequest);
        }

        return redirect()->route('table.edit', $table->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TableRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);

        $request->merge(['id' => $id]);
        $table = (new Table())->get($request)->load(['database', 'columns', 'columns.endpoints', 'columns.screens']);

        $currentCompany = (isset($table[0]) && isset($table[0]?->database) && $table[0]?->database?->company_id)
            ? $table[0]?->database?->company_id
            : $request->cookie('currentCompany');

        Cookie::queue('currentCompany', $currentCompany, 60);
        $request->cookies->set('currentCompany', $currentCompany);

        $databaseRequest->merge(['company_id' => $currentCompany]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = (isset($table[0]) && $table[0]->database_id)
            ? $table[0]->database_id
            : (new DatabaseController())->getLatest($request)['id'];

        Cookie::queue('currentDatabase', $currentDatabase, 60);
        $request->cookies->set('currentDatabase', $currentDatabase);

        $doc = null;
        if (isset($table[0]) && $table[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $table[0]->doc_file);
        }

        return Inertia::render('resources/table/view', [
            'table' => Inertia::always($table[0]),
            'doc' => $doc,
            'databases' => $databases,
        ])->with([
            'currentDatabase' => $currentDatabase,
            'currentCompany' => $currentCompany,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, TableRequest $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);

        $request->merge(['id' => $id]);
        $table = (new Table())->get($request)->load(['database']);

        $currentCompany = (isset($table[0]) && isset($table[0]?->database) && $table[0]?->database?->company_id)
            ? $table[0]?->database?->company_id
            : $request->cookie('currentCompany');

        Cookie::queue('currentCompany', $currentCompany, 60);
        $request->cookies->set('currentCompany', $currentCompany);

        $databaseRequest->merge(['company_id' => $currentCompany]);
        $databases = (new DatabaseController())->get($databaseRequest);

        $currentDatabase = (isset($table[0]) && $table[0]->database_id)
            ? $table[0]->database_id
            : (new DatabaseController())->getLatest($request)['id'];

        Cookie::queue('currentDatabase', $currentDatabase, 60);
        $request->cookies->set('currentDatabase', $currentDatabase);

        $doc = null;
        if (isset($table[0]) && $table[0]->doc_file) {
            $doc = (new FileRepository())->get('docs', $table[0]->doc_file);
        }

        return Inertia::render('resources/table/manipulate', [
            'table' => $table,
            'doc' => $doc,
            'databases' => $databases,
        ])->with([
            'currentDatabase' => $currentDatabase,
            'currentCompany' => $currentCompany,
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

        if ($request->has('markdown') && is_string($request->markdown) && mb_strlen($request->markdown) > 0) {
            $fileRequest = (new HandleStringToFile())->handle($request);
            (new FileController())->storeTable($fileRequest);
        }

        return redirect()->route('table.edit', $id);
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

        $fileRequest = FileRequest::createFrom($request);
        (new FileController())->deleteTable($fileRequest);

        return response()->json($response);
    }
}

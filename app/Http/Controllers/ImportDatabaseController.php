<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatabaseRequest;
use App\Http\Requests\ImportDatabaseRequest;
use App\Services\ParseSqlService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ImportDatabaseController extends Controller
{
    public static function index(Request $request): Response
    {
        $databaseRequest = DatabaseRequest::createFrom($request);
        $databaseRequest->merge(['company_id' => $request->cookie('currentCompany')]);

        $databases = (new DatabaseController())->get($databaseRequest);

        return Inertia::render('resources/database/import', [
            'databases' => $databases
        ]);
    }

    public static function store(ImportDatabaseRequest $request)
    {
        $sql = file_get_contents($request->file('file')->getRealPath());
        $parsedSql = (new ParseSqlService)->extractTables($sql);

        dd($parsedSql);

        return $parsedSql;
    }
}

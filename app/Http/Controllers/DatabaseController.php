<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use App\Repositories\DatabaseRepository;
use Illuminate\Database\Eloquent\Collection;

class DatabaseController extends Controller
{
    public function store(DatabaseRequest $request): Database
    {
        return (new DatabaseRepository())->create($request);
    }

    public function get(DatabaseRequest $request): Collection
    {
        if ($request->has('id')) {
            (new DatabaseRequest())->ensureDatabaseBelongsToUser($request);
        }

        return (new DatabaseRepository())->get($request);
    }
}

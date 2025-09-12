<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TableRequest;
use App\Models\Table;
use App\Repositories\TableRepository;
use Illuminate\Database\Eloquent\Collection;

class TableController extends Controller
{
    public function store(TableRequest $request): Table
    {
        return (new TableRepository())->create($request);
    }

    public function get(TableRequest $request): Collection
    {
        if ($request->has('id')) {
            (new TableRequest())->ensureTableBelongsToUser($request);
        }

        return (new TableRepository())->get($request);
    }
}

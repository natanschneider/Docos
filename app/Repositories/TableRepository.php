<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\TableRequest;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

final class TableRepository
{
    public function create(TableRequest $request): Table
    {
        return Table::create($request->all());
    }

    public function get(TableRequest $request): Collection
    {
        $query = Table::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('database_id')) {
            $query->where('database_id', $request->database_id);
        }

        return $query->get();
    }
}

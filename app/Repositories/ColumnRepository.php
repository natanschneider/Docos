<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

final class ColumnRepository
{
    public function create(ColumnRequest $request): Column
    {
        return Column::create($request->all());
    }

    public function get(ColumnRequest $request): Collection
    {
        $query = Column::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('table_id')) {
            $query->where('table_id', $request->table_id);
        }

        return $query->get();
    }
}

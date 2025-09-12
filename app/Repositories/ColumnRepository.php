<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;

final class ColumnRepository
{
    public function create(ColumnRequest $request): Column
    {
        return Column::create($request->all());
    }
}

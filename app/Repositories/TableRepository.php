<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\TableRequest;
use App\Models\Table;

final class TableRepository
{
    public function create(TableRequest $request): Table
    {
        return Table::create($request->all());
    }
}

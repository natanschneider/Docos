<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;

final class DatabaseRepository
{
    public function create(DatabaseRequest $request): Database
    {
        return Database::create($request->all());
    }
}

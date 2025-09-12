<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use Illuminate\Database\Eloquent\Collection;

final class DatabaseRepository
{
    public function create(DatabaseRequest $request): Database
    {
        return Database::create($request->all());
    }

    public function get(DatabaseRequest $request): Collection
    {
        $query = Database::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        return $query->get();
    }
}

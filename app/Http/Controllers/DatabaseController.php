<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use App\Repositories\DatabaseRepository;

class DatabaseController extends Controller
{
    public function store(DatabaseRequest $request): Database
    {
        return (new DatabaseRepository())->create($request);
    }
}

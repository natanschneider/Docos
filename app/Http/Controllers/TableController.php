<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TableRequest;
use App\Models\Table;
use App\Repositories\TableRepository;

class TableController extends Controller
{
    public function store(TableRequest $request): Table
    {
        return (new TableRepository())->create($request);
    }
}

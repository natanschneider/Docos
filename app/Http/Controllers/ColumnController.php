<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Repositories\ColumnRepository;

class ColumnController extends Controller
{
    public function store(ColumnRequest $request): Column
    {
        return (new ColumnRepository())->create($request);
    }
}

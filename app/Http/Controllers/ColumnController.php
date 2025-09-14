<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use Illuminate\Database\Eloquent\Collection;

class ColumnController extends Controller
{
    public function store(ColumnRequest $request): Column
    {
        return (new ColumnRepository())->create($request);
    }

    public function get(ColumnRequest $request): Collection
    {
        if ($request->has('id')) {
            (new ColumnRequest())->unsureColumnBelongsToUser($request);
        }

        return (new ColumnRepository())->get($request);
    }

    public function update(ColumnRequest $request): Column
    {
        (new ColumnRequest())->unsureColumnBelongsToUser($request);

        return (new ColumnRepository())->update($request);
    }

    public function destroy(ColumnRequest $request): Column|Collection
    {
        (new ColumnRequest())->unsureColumnBelongsToUser($request);

        return (new ColumnRepository())->destroy($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ScreenRequest;
use App\Models\Screen;
use App\Repositories\ScreenRepository;
use Illuminate\Database\Eloquent\Collection;

class ScreenController extends Controller
{
    public function store(ScreenRequest $request): Screen
    {
        return (new ScreenRepository())->create($request);
    }

    public function get(ScreenRequest $request): Collection
    {
        if ($request->has('id')) {
            (new ScreenRequest())->ensureScreenBelongsToUser($request);
        }

        return (new ScreenRepository())->get($request);
    }

    public function update(ScreenRequest $request): Collection
    {
        (new ScreenRequest())->ensureScreenBelongsToUser($request);

        return (new ScreenRepository())->update($request);
    }
}

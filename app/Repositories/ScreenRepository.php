<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ScreenRequest;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Collection;

final class ScreenRepository
{
    public function create(ScreenRequest $request): Screen
    {
        return Screen::create($request->all());
    }

    public function get(ScreenRequest $request): Collection
    {
        $query = Screen::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('application_id')) {
            $query->where('application_id', $request->application_id);
        }

        return $query->get();
    }
}

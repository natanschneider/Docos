<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ScreenRequest;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ScreenRepository
{
    public function create(ScreenRequest $request): Screen
    {
        return DB::transaction(function () use ($request): Screen {
            $screen = Screen::create($request->only([
                'name',
                'application_id',
            ]));

            if ($request->has('columns')) {
                $screen->columns()->attach($request->columns);
            }

            return $screen->load('columns');
        });
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

        $query->whereHas('application.project.company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->with('columns')->get();
    }

    public function update(ScreenRequest $request): Screen
    {
        return DB::transaction(function () use ($request): Screen {
            $screen = Screen::findOrFail($request->id);

            $screen->update($request->only([
                'name',
                'application_id',
            ]));

            if ($request->has('columns')) {
                $screen->columns()->syncWithoutDetaching($request->columns);
            }

            if ($request->has('detach_columns')) {
                $screen->columns()->detach($request->detach_columns);
            }

            return $screen->load('columns');
        });
    }

    public function destroy(ScreenRequest $request): Screen
    {
        return DB::transaction(function () use ($request) {
            $screen = Screen::find($request->id);

            $screen->columns()->detach();
            $screen->delete();

            return $screen->load('columns');
        });
    }
}

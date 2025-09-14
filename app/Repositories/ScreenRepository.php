<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ScreenRequest;
use App\Models\Application;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ScreenRepository
{
    public function create(ScreenRequest $request): Collection
    {
        return DB::transaction(function () use ($request): Collection {
            $screen = Screen::create($request->only([
                'name',
                'application_id',
            ]));

            if ($request->has('columns')) {
                $screen->columns()->attach($request->columns);
            }

            return $screen->with('columns')->get();
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

        return $query->with('columns')->get();
    }

    public function update(ScreenRequest $request): Collection
    {
        if ($request->has('application_id')) {
            $company = Application::findOrFail($request->application_id)->project->company;

            if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
                abort(403, 'Application does not belong to user or does not exist');
            }
        }

        DB::transaction(function () use ($request): void {
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
        });

        return Screen::findOrFail($request->id)->with('columns')->get();
    }

    public function destroy(ScreenRequest $request): Collection
    {
        return DB::transaction(function () use ($request) {
            $screen = Screen::find($request->id);

            $screen->delete();
            $screen->columns()->detach();

            return $screen->with('columns')->get();
        });
    }
}

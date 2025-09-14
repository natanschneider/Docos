<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ScreenRequest;
use App\Models\Application;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Collection;

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
            $company = Application::where('id', $request->application_id)->project->company;

            if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
                abort(403, 'Application does not belong to user or does not exist');
            }
        }

        Screen::where('id', $request->id)->update($request->all());

        return Screen::where('id', $request->id)->get();
    }

    public function destroy(ScreenRequest $request): Screen|Collection
    {
        $screen = Screen::find($request->id);

        $screen->delete();

        return $screen;
    }
}

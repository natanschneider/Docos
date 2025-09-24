<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CompanyRequest;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

final class CompanyController extends Controller
{
    public function store(CompanyRequest $request): Company
    {
        Gate::authorize('create', Company::class);
        return (new CompanyRepository())->create($request);
    }

    public function get(CompanyRequest $request): Collection
    {
        if ($request->has('id')) {
            Gate::authorize('view', Company::findOrFail($request->id));
        }

        return (new CompanyRepository())->get($request);
    }

    public function update(CompanyRequest $request): Collection
    {
        Gate::authorize('update', Company::findOrFail($request->id));

        return (new CompanyRepository())->update($request);
    }

    public function destroy(CompanyRequest $request): Company|Collection
    {
        Gate::authorize('delete', Company::findOrFail($request->id));

        return (new CompanyRepository())->delete($request);
    }
}

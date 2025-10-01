<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\Pivot\UserCompany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class CompanyRepository
{
    public function create(CompanyRequest $request): Company
    {
        return DB::transaction(function () use ($request) {
            $company = Company::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            UserCompany::create([
                'user_id' => $request->user()->id,
                'company_id' => $company->id,
            ]);

            return $company;
        });
    }

    public function get(CompanyRequest $request): Collection
    {
        $query = $request->user()->companies();

        if ($request->has('id')) {
            $query->where('companies.id', $request->id);
        }

        return $query->get();
    }

    public function update(CompanyRequest $request): Collection
    {
        Company::where('id', $request->id)->update($request->all());

        return Company::where('id', $request->id)->get();
    }

    public function delete(CompanyRequest $request): Company|Collection
    {
        return DB::transaction(function () use ($request) {
            $company = Company::find($request->id);

            $company->users()->detach();
            $company->projects()->delete();
            $company->databases()->delete();
            $company->delete();

            if (Company::where('id', $request->id)->exists()) {
                abort(500);
            }

            return $company;
        });
    }
}

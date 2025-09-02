<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\UserCompany;
use Illuminate\Support\Facades\DB;

final class CompanyRepository
{
    public function create(CompanyRequest $request): Company
    {
        return DB::transaction(function () use ($request) {
            $company = Company::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => true,
            ]);

            UserCompany::create([
                'user_id' => $request->user()->id,
                'company_id' => $company->id,
            ]);

            return $company;
        });
    }
}

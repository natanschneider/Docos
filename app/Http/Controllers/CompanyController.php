<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

final class CompanyController extends Controller
{
    public function store(CompanyRequest $request): Company
    {
        return (new CompanyRepository())->create($request);
    }

    public function get(CompanyRequest $request): Collection
    {
        return (new CompanyRepository())->get($request);
    }

    public function update(CompanyRequest $request): Collection
    {
        (new CompanyRequest())->ensureCompanyBelongsToUser($request);

        return (new CompanyRepository())->update($request);
    }

    public function destroy(CompanyRequest $request): Company|Collection
    {
        (new CompanyRequest())->ensureCompanyBelongsToUser($request);
        (new CompanyRequest())->ensureCompanyIsEmpty($request);

        return (new CompanyRepository())->delete($request);
    }
}

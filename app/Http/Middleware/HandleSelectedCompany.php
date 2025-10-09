<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Company;
use Illuminate\Http\Request;

class HandleSelectedCompany
{
    public function handle(Request $request): ?int
    {
        return $request->hasCookie('currentCompany') ? (int) $request->cookie('currentCompany') : $this->getLatestCompany($request);
    }

    private function getLatestCompany(Request $request): ?int
    {
        $companies = Company::query();
        $companies->whereIn('companies.id', $request->user()->companies()->pluck('companies.id'));
        $company = $companies->latest()->first(['id'])->toArray();

        $company = isset($company['id']) ? (int) $company['id'] : null;

        if ($company !== null && $company !== 0) {
            $request->cookies->set('currentCompany', $company);
        }

        return $company;
    }
}

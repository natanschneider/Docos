<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Company;
use Illuminate\Http\Request;

class HandleSelectedCompany
{
    public function handle(Request $request): ?int
    {
        if ($request->user() === null) {
            return null;
        }

        if ($request->hasCookie('currentCompany')) {
            $this->ensureCompanyBelongsToUser($request);
        }

        return $request->hasCookie('currentCompany') ? (int) $request->cookie('currentCompany') : $this->getLatestCompany($request);
    }

    private function getLatestCompany(Request $request): ?int
    {
        if (! $request->user()) {
            return null;
        }
        if ($request->user()->companies()->doesntExist()) {
            return null;
        }

        $companies = Company::query();
        $companies->whereIn('companies.id', $request?->user()?->companies()?->pluck('companies.id'));
        $company = $companies->latest()->first(['id'])->toArray();

        $company = isset($company['id']) ? (int) $company['id'] : null;

        if ($company !== null && $company !== 0) {
            $request->cookies->set('currentCompany', $company);
        }

        return $company;
    }

    private function ensureCompanyBelongsToUser(Request $request): int
    {
        $company = (int) $request->cookie('currentCompany');

        return $request->user()->companies()->where('companies.id', $company)->exists()
            ? $company
            : abort(403, 'Company provided does not belong to user or does not exist');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SlugController extends Controller
{
    public function company(string $slug): RedirectResponse|bool
    {
        [$title, $companyId] = $this->explodeSlug($slug);

        $company = Company::findOrFail($companyId, ['public_key'])->first();

        $dbTitle = Str::slug($company->name, '-');

        if ($title !== $dbTitle) {
            return redirect()->route('company', ["$dbTitle-$company->public_key"]);
        }

        return true;
    }

    private function explodeSlug(string $slug): array
    {
        $parts = array_reverse(explode('-', strrev($slug), 2));

        return array_map(strrev(...), $parts);
    }
}

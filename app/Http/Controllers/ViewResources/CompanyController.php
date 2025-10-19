<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\CompanyController as Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CompanyRequest $request)
    {
        $companies = (new Company)->get($request);

        return Inertia::render('resources/company/list', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('resources/company/manipulate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request): void
    {
        (new Company)->store($request);

        back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyRequest $request)
    {
        $company = (new Company)->get($request);

        return Inertia::render('resources/company/manipulate', [
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyRequest $request)
    {
        $company = (new Company)->get($request);

        return Inertia::render('resources/company/manipulate', [
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request): void
    {
        (new Company)->update($request);

        back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyRequest $request): void
    {
        (new Company)->destroy($request);

        back();
    }
}

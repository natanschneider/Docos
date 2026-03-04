<?php

declare(strict_types=1);

use App\Repositories\ViewsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function (Request $request) {
        return Inertia::render('dashboard', [
            'categories' => (new ViewsRepository())->dashboard($request),
        ]);
    })->name('dashboard');
});

require __DIR__.'/resources.php';
require __DIR__.'/SelectResource.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

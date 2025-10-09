<?php

declare(strict_types=1);

use App\Http\Controllers\ViewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function (Request $request) {
        return Inertia::render('dashboard', [
            'categories' => (new ViewsController())->dashboard($request),
        ]);
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

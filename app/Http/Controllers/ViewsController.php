<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ViewsRepository;
use Illuminate\Http\Request;

class ViewsController extends Controller
{
    public function dashboard(Request $request): array
    {
        return (new ViewsRepository())->dashboard($request);
    }
}

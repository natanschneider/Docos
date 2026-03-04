<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\TableController;
use Closure;
use Illuminate\Http\Request;

class HandleSelectedTable
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request)
    {
        if (! $request->user()) {
            return null;
        }

        $id = ($request->hasCookie('currentTable') && $request->cookie('currentTable')) ? (int) $request->cookie('currentTable') : (new TableController())->getLatest($request)['id'];

        if ($id === 0 || $id === '0') {
            $id = null;
        }

        $request->cookies->set('currentTable', $id);

        return $id;
    }
}

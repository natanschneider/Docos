<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Models\Column;
use App\Models\Endpoint;
use App\Models\Screen;
use App\Models\Table;
use App\Repositories\Files\ColumnFileRepository;
use App\Repositories\Files\EndpointFileRepository;
use App\Repositories\Files\ScreenFileRepository;
use App\Repositories\Files\TableFileRepository;
use Illuminate\Support\Facades\Gate;

class FileController extends Controller
{
    public function storeColumn(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Column::findOrFail($request->id), $request]);

        return (new ColumnFileRepository())->upload($request, Column::findOrFail($request->id));
    }

    public function deleteColumn(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Column::findOrFail($request->id), $request]);

        return (new ColumnFileRepository())->delete(Column::findOrFail($request->id));
    }

    public function storeEndpoint(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Endpoint::findOrFail($request->id), $request]);

        return (new EndpointFileRepository())->upload($request, Endpoint::findOrFail($request->id));
    }

    public function deleteEndpoint(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Endpoint::findOrFail($request->id), $request]);

        return (new EndpointFileRepository())->delete(Endpoint::findOrFail($request->id));
    }

    public function storeScreen(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Screen::findOrFail($request->id), $request]);

        return (new ScreenFileRepository())->upload($request, Screen::findOrFail($request->id));
    }

    public function deleteScreen(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Screen::findOrFail($request->id), $request]);

        return (new ScreenFileRepository())->delete(Screen::findOrFail($request->id));
    }

    public function storeTable(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Table::findOrFail($request->id), $request]);

        return (new TableFileRepository())->upload($request, Table::findOrFail($request->id));
    }

    public function deleteTable(FileRequest $request): array
    {
        Gate::authorize('handleFiles', [Table::findOrFail($request->id), $request]);

        return (new TableFileRepository())->delete(Table::findOrFail($request->id));
    }
}

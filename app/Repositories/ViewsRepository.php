<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Application;
use App\Models\Column;
use App\Models\Database;
use App\Models\Endpoint;
use App\Models\Project;
use App\Models\Screen;
use App\Models\Table;
use Illuminate\Http\Request;

final class ViewsRepository
{
    public function dashboard(Request $request): array
    {
        $companyId = $request->cookie('currentCompany');

        $databases = Database::whereRelation('company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        $tables = Table::whereRelation('database.company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        $columns = Column::whereRelation('table.database.company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        $project = Project::whereRelation('company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        $applications = Application::whereRelation('project.company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        $screens = Screen::whereRelation('application.project.company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        $endpoints = Endpoint::whereRelation('application.project.company', 'id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->offset(0)
            ->limit(5)
            ->get(['id', 'name', 'updated_at'])
            ->toArray();

        return [
            [
                'id' => 'databases',
                'name' => 'Databases',
                'items' => $databases,
            ],
            [
                'id' => 'tables',
                'name' => 'Tables',
                'items' => $tables,
            ],
            [
                'id' => 'columns',
                'name' => 'Columns',
                'items' => $columns,
            ],
            [
                'id' => 'projects',
                'name' => 'Projects',
                'items' => $project,
            ],
            [
                'id' => 'applications',
                'name' => 'Applications',
                'items' => $applications,
            ],
            [
                'id' => 'screens',
                'name' => 'Screens',
                'items' => $screens,
            ],
            [
                'id' => 'endpoints',
                'name' => 'Endpoints',
                'items' => $endpoints,
            ],
        ];
    }
}

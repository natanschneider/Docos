<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class engines extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $engines = [
            ['id' => 1, 'name' => 'MySQL'],
            ['id' => 2, 'name' => 'PostgreSQL'],
            ['id' => 3, 'name' => 'SQLite'],
            ['id' => 4, 'name' => 'SQL Server'],
            ['id' => 5, 'name' => 'MariaDB'],
            ['id' => 6, 'name' => 'Oracle'],
        ];

        DB::table('engines')->insert($engines);
    }
}

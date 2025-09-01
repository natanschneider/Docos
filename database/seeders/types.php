<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class types extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataTypes = [
            ['id' => 1, 'name' => 'Integer'],
            ['id' => 2, 'name' => 'Float'],
            ['id' => 3, 'name' => 'Double'],
            ['id' => 4, 'name' => 'String'],
            ['id' => 5, 'name' => 'Text'],
            ['id' => 6, 'name' => 'Boolean'],
            ['id' => 7, 'name' => 'Date'],
            ['id' => 8, 'name' => 'DateTime'],
            ['id' => 9, 'name' => 'Time'],
            ['id' => 10, 'name' => 'Binary'],
            ['id' => 11, 'name' => 'JSON'],
            ['id' => 12, 'name' => 'UUID'],
        ];

        DB::table('types')->insert($dataTypes);
    }
}

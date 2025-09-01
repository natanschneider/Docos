<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class constraints extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $constraints = [
            ['id' => 1, 'name' => 'Primary Key'],
            ['id' => 2, 'name' => 'Foreign Key'],
            ['id' => 3, 'name' => 'Unique'],
            ['id' => 4, 'name' => 'Check'],
            ['id' => 5, 'name' => 'Not Null'],
            ['id' => 6, 'name' => 'Default'],
            ['id' => 7, 'name' => 'Index'],
            ['id' => 8, 'name' => 'Composite Key'],
            ['id' => 9, 'name' => 'Foreign Key with Cascade'],
            ['id' => 10, 'name' => 'Foreign Key with Set Null'],
        ];

        DB::table('constraints')->insert($constraints);
    }
}

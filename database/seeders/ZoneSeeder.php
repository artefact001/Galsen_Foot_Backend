<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('zones')->insert([
            [
                'nom' => 'Zone 1',
                'localite' => 'Dakar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Zone 2',
                'localite' => 'Thiès',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Zone 3',
                'localite' => 'Saint-Louis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

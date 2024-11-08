<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionEquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('competition_equipe')->insert([
            [
                'equipe_id' => 1,
                'competition_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipe_id' => 2,
                'competition_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipe_id' => 3,
                'competition_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

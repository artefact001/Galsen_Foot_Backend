<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classements')->insert([
            [
                'zone_id' => 1, // Zone 1
                'equipe_id' => 1, // Équipe 1
                'matches_joues' => 10,
                'gagne' => 6,
                'nul' => 3,
                'perdu' => 1,
                'buts_marques' => 18,
                'buts_encaisses' => 10,
                'diff_buts' => 8,
                'points' => 21,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'zone_id' => 1, // Zone 1
                'equipe_id' => 2, // Équipe 2
                'matches_joues' => 10,
                'gagne' => 4,
                'nul' => 4,
                'perdu' => 2,
                'buts_marques' => 14,
                'buts_encaisses' => 12,
                'diff_buts' => 2,
                'points' => 16,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'zone_id' => 1, // Zone 1
                'equipe_id' => 3, // Équipe 3
                'matches_joues' => 10,
                'gagne' => 3,
                'nul' => 3,
                'perdu' => 4,
                'buts_marques' => 11,
                'buts_encaisses' => 15,
                'diff_buts' => -4,
                'points' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

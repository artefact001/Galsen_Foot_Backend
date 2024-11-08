<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('competitions')->insert([
            [
                'nom' => 'Championnat National',
                'date_debut' => '2024-05-01',
                'date_fin' => '2024-06-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Tournoi International',
                'date_debut' => '2024-07-10',
                'date_fin' => '2024-07-25',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Coupe Régionale',
                'date_debut' => '2024-08-01',
                'date_fin' => '2024-08-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

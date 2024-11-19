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
                'lieux' => 'Leopold S Senghor',
                
            ],
            [
                'nom' => 'Tournoi International',
                'date_debut' => '2024-07-10',
                'date_fin' => '2024-07-25',
                'lieux' => 'Amadou Barry',

         
            ],
            [
                'nom' => 'Coupe Régionale',
                'date_debut' => '2024-08-01',
                'date_fin' => '2024-08-20',
                'lieux' => 'Demba Diop',

            ],
        ]);
    }
}

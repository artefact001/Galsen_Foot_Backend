<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            UsersTableSeeder::class,
            ZoneSeeder::class,
            EquipeSeeder::class,
            CategorieSeeder::class,
            EvenementSeeder::class,
            CompetitionSeeder::class,
            JoueurSeeder::class,
            TirageSeeder::class,
            MatcheSeeder::class,
            ReclamationsSeeder::class,
            StatistiquesSeeder::class,
            ClassementsSeeder::class,
            PointsSeeder::class,
            HistoriqueJoueurEquipeSeeder::class,
            StatistiqueJoueurSeeder::class,
            
            
        ]);
    }
}

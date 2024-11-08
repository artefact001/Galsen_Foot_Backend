<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatistiqueJoueurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statistiques_joueurs')->insert([
            [
                'joueur_id' => 1,  // Assurez-vous que le joueur 1 existe dans la table 'joueurs'
                'matche_id' => 1,  // Assurez-vous que le match 1 existe dans la table 'matches'
                'buts' => 2,  // Nombre de buts marqués par le joueur
                'passeurs' => 1,  // Nombre de passes décisives
                'cartons_jaunes' => 0,  // Nombre de cartons jaunes reçus
                'cartons_rouges' => 0,  // Nombre de cartons rouges reçus
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'joueur_id' => 2,  // Joueur 2
                'matche_id' => 1,  // Match 1
                'buts' => 1,
                'passeurs' => 2,
                'cartons_jaunes' => 1,
                'cartons_rouges' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'joueur_id' => 1,  // Joueur 1
                'matche_id' => 2,  // Match 2
                'buts' => 0,
                'passeurs' => 1,
                'cartons_jaunes' => 1,
                'cartons_rouges' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

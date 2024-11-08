<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoriqueJoueurEquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('historique_joueur_equipe')->insert([
            [
                'joueur_id' => 1,  // Assurez-vous que le joueur 1 existe dans la table 'joueurs'
                'equipe_id' => 1,  // Assurez-vous que l'équipe 1 existe dans la table 'equipes'
                'date_debut' => '2023-01-15',  // Date de début d'affiliation
                'date_fin' => null,  // Actuellement actif
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'joueur_id' => 2,  // Joueur 2
                'equipe_id' => 1,  // Équipe 1
                'date_debut' => '2023-02-10',
                'date_fin' => null,  // Actuellement actif
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'joueur_id' => 1,  // Joueur 1
                'equipe_id' => 2,  // Équipe 2
                'date_debut' => '2024-05-01',
                'date_fin' => '2024-08-01',  // Ancienne affiliation
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}

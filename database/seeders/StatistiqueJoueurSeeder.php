<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Joueur;
use App\Models\Matche;
use Illuminate\Support\Facades\DB;

class StatistiqueJoueurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer tous les joueurs et matchs existants
        $joueurs = Joueur::all();
        $matches = Matche::all();

        // Générer 50 enregistrements de statistiques de joueur
        for ($i = 0; $i < 50; $i++) {
            // Sélectionner un joueur et un match aléatoires
            $joueur = $joueurs->random();
            $match = $matches->random();

            // Insérer une statistique aléatoire pour ce joueur dans ce match
            DB::table('statistiques_joueurs')->insert([
                'joueur_id' => $joueur->id,
                'matche_id' => $match->id,
                'buts' => rand(0, 3),  // Nombre aléatoire de buts
                'passeurs' => rand(0, 3),  // Nombre aléatoire de passes décisives
                'cartons_jaunes' => rand(0, 2),  // Nombre aléatoire de cartons jaunes
                'cartons_rouges' => rand(0, 1),  // Nombre aléatoire de cartons rouges
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

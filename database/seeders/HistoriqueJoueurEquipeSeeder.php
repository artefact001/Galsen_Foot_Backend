<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Joueur;
use App\Models\Equipe;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriqueJoueurEquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer tous les joueurs et équipes existants
        $joueurs = Joueur::all();
        $equipes = Equipe::all();

        // Générer 50 enregistrements d'historique
        for ($i = 0; $i < 50; $i++) {
            // Sélectionner un joueur et une équipe aléatoires
            $joueur = $joueurs->random();
            $equipe = $equipes->random();

            // Générer une date de début aléatoire
            $dateDebut = Carbon::now()->subYears(rand(1, 5))->subMonths(rand(1, 12));
            
            // Générer une date de fin aléatoire (parfois nul pour signifier l'affiliation actuelle)
            $dateFin = rand(0, 1) ? $dateDebut->copy()->addMonths(rand(6, 24)) : null;

            // Insérer l'enregistrement dans la table
            DB::table('historique_joueur_equipe')->insert([
                'joueur_id' => $joueur->id,
                'equipe_id' => $equipe->id,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

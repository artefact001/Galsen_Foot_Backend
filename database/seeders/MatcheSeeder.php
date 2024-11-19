<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matche;
use App\Models\Equipe;
use App\Models\Competition;
use Carbon\Carbon;

class MatcheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Récupérer la compétition existante ou en créer une
        $competition = Competition::firstOrCreate([
            'nom' => 'Compétition Test',
            'date_debut' => Carbon::now()->subWeeks(2),
            'date_fin' => Carbon::now()->addWeeks(2),
            'lieux' => 'Stade National',
        ]);

        // Récupérer les équipes de la zone 1
        $equipes = Equipe::where('zone_id', 1)->get();

        // Créer 20 matchs
        for ($i = 0; $i < 20; $i++) {
            // Choisir deux équipes différentes aléatoirement
            $equipesSelectionnees = $equipes->random(2);
            $equipe1 = $equipesSelectionnees[0];
            $equipe2 = $equipesSelectionnees[1];

            // Générer des scores aléatoires
            $scoreEquipe1 = rand(0, 5);
            $scoreEquipe2 = rand(0, 5);

            // Déterminer le résultat du match
            $resultat = $scoreEquipe1 > $scoreEquipe2 ? 'gagne' : ($scoreEquipe1 < $scoreEquipe2 ? 'perdu' : 'nul');

            // Définir le statut et la date du match
            $dateMatch = Carbon::now()->subDays(rand(1, 30));
            $statut = $dateMatch < Carbon::now() ? 'termine' : 'en_attente';

            // Créer le match
            Matche::create([
                'competition_id' => $competition->id,
                'equipe1_id' => $equipe1->id,
                'equipe2_id' => $equipe2->id,
                'score_equipe1' => $scoreEquipe1,
                'score_equipe2' => $scoreEquipe2,
                'date_matche' => $dateMatch,
                'lieux' => 'Stade Local',
                'statut' => $statut,
                'buteurs' => json_encode([]), // Ajouter des buteurs si nécessaire
                'passeurs' => json_encode([]), // Ajouter des passeurs si nécessaire
                'homme_du_matche' => null,
                'cartons' => json_encode([]),
                'resultat' => $resultat,
            ]);
        }
    }
}

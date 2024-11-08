<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipe;
use App\Models\Matche;
use App\Models\Competition;
use Carbon\Carbon;

class MatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ensure that a competition exists
        $competition = Competition::firstOrCreate([
            'nom' => 'Compétition Test',
            'date_debut' => Carbon::now()->subWeeks(2),
            'date_fin' => Carbon::now()->addWeeks(2),
        ]);

        // Fetch teams from zone 1
        $equipes = Equipe::where('zone_id', 1)->get();

        // Create matches between teams in this zone
        foreach ($equipes->chunk(2) as $chunk) {
            if ($chunk->count() == 2) { // Ensure we have two teams for a match
                $scoreEquipe1 = rand(0, 5);
                $scoreEquipe2 = rand(0, 5);

                // Determine the match result
                $resultat = $scoreEquipe1 > $scoreEquipe2 ? 'gagne' : ($scoreEquipe1 < $scoreEquipe2 ? 'perdu' : 'nul');

                // Set match status based on match date
                $matchDate = Carbon::now()->subDays(rand(1, 10));
                $statut = $matchDate < Carbon::now() ? 'termine' : 'en cours';

                Matche::create([
                    'competition_id' => $competition->id,
                    'equipe1_id' => $chunk[0]->id,
                    'equipe2_id' => $chunk[1]->id,
                    'score_equipe1' => $scoreEquipe1,
                    'score_equipe2' => $scoreEquipe2,
                    'date_matche' => $matchDate,
                    'statut' => $statut,
                    'buteurs' => json_encode([]), // Update with actual scorers if necessary
                    'passeurs' => json_encode([]), // Update with actual assisters if necessary
                    'homme_du_matche' => null,
                    'cartons' => json_encode([]),
                    'resultat' => $resultat,
                ]);
            }
        }
    }
}

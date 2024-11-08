<?php

namespace App\Services;

use App\Models\Resultat;

class ResultatService
{
    // Create or update a result with goal scorers, passers, and "man of the match"
    public function storeResultat(array $data)
    {
        $resultat = Resultat::updateOrCreate(
            ['matche_id' =>2, 'equipe_id' => $data['equipe_id'], 
                'score' => $data['score'],
                'is_winner' => $data['is_winner'],
                'buteurs' => $data['buteurs'], // Array of goal scorer IDs
                'passeurs' => $data['passeurs'], // Array of passer IDs
                'homme_du_matche' => $data['homme_du_matche'], // ID of the "man of the match"
            ]
        );

        return $resultat;
    }

    // Get all results for a given match
    public function getResultatsByMatch($matchId)
    {
        return Resultat::where('matche_id', $matchId)->with('equipe', 'hommeDuMatch')->get();
    }

    // Determine and update the winning team
    public function determineWinner($matchId)
    {
        $resultats = Resultat::where('matche_id', $matchId)->get();
        $winner = $resultats->sortByDesc('score')->first();

        foreach ($resultats as $resultat) {
            $resultat->update(['is_winner' => $resultat->id == $winner->id]);
        }

        return $winner->equipe;
    }
}

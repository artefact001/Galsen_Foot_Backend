<?php

namespace App\Services;

use App\Models\Matche;
use App\Models\Classement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MatcheService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getAllMatches(): Collection
    {
        return Matche::with(['equipe1', 'equipe2', 'competition'])->get();
    }

    public function getMatchById(int $id): Matche
    {
        return Matche::with(['equipe1', 'equipe2', 'competition', 'hommeDuMatch'])->findOrFail($id);
    }

    public function createMatche(array $data): Matche
    {
        return DB::transaction(function () use ($data) {
            // $data['statut'] = $data['statut'] ?? 'en_attente';
            return Matche::create($data);
        });
    }

    public function updateMatche(Matche $matche, array $data): Matche
    {
        return DB::transaction(function () use ($matche, $data) {
            if (isset($data['statut']) && $data['statut'] === 'termine') {
                return $this->terminerMatche($matche, $data);
            }

            $matche->update($data);
            return $matche;
        });
    }

    protected function terminerMatche(Matche $matche, array $data): Matche
    {
        // Update match data
        $matche->update([
            'score_equipe1' => $data['score_equipe1'],
            'score_equipe2' => $data['score_equipe2'],
            'equipe1_is_winner' => $data['equipe1_is_winner'],
            'equipe2_is_winner' => $data['equipe2_is_winner'],
            'buteurs' => $data['buteurs'] ?? [],
            'passeurs' => $data['passeurs'] ?? [],
            'cartons' => $data['cartons'] ?? [],
            'homme_du_matche' => $data['homme_du_matche'] ?? null,
            'statut' => 'termine'
        ]);

        // Update standings
        $this->updateStandings($matche);

        // Send notifications
        $this->notificationService->notifyMatchResult($matche);

        return $matche->fresh(['equipe1', 'equipe2', 'competition', 'hommeDuMatch']);
    }

    protected function updateStandings(Matche $matche): void
    {
        $this->updateTeamStanding($matche->equipe1_id, $matche);
        $this->updateTeamStanding($matche->equipe2_id, $matche);
    }

    protected function updateTeamStanding(int $equipeId, Matche $matche): void
    {
        $isEquipe1 = $equipeId === $matche->equipe1_id;
        $score = $isEquipe1 ? $matche->score_equipe1 : $matche->score_equipe2;
        $scoreOpposant = $isEquipe1 ? $matche->score_equipe2 : $matche->score_equipe1;
        $isWinner = $isEquipe1 ? $matche->equipe1_is_winner : $matche->equipe2_is_winner;

        $classement = Classement::firstOrCreate(
            ['equipe_id' => $equipeId],
            [
                'matches_joues' => 0,
                'victoires' => 0,
                'nuls' => 0,
                'defaites' => 0,
                'buts_pour' => 0,
                'buts_contre' => 0,
                'points' => 0,
                'diff_buts' => 0
            ]
        );

        $classement->matches_joues++;
        $classement->buts_pour += $score;
        $classement->buts_contre += $scoreOpposant;

        if ($isWinner) {
            $classement->victoires++;
            $classement->points += 3;
        } elseif ($score === $scoreOpposant) {
            $classement->nuls++;
            $classement->points += 1;
        } else {
            $classement->defaites++;
        }

        $classement->diff_buts = $classement->buts_pour - $classement->buts_contre;
        $classement->save();
    }

    public function deleteMatche(Matche $matche): bool
    {
        return DB::transaction(function () use ($matche) {
            return $matche->delete();
        });
    }
}

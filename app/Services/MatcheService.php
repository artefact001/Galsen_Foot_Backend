<?php
namespace App\Services;

use App\Models\Matche;
use App\Models\Classement;
use App\Models\Competition;
use App\Models\Equipe;
use App\Models\Joueur;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MatcheService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Récupérer tous les matchs
    public function getAllMatches(): Collection
    {
        return Matche::with(['equipe1', 'equipe2', 'competition'])->get();
    }

    // Récupérer un match par son ID
    public function getMatchById(int $id): Matche
    {
        return Matche::with(['equipe1', 'equipe2', 'competition', 'hommeDuMatch'])->findOrFail($id);
    }

    // Créer un match
    public function createMatche(array $data): Matche
    {
        return DB::transaction(function () use ($data) {
            return Matche::create($data);
        });
    }

    // Mettre à jour un match
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

    // Terminer un match et mettre à jour les classements
    protected function terminerMatche(Matche $matche, array $data): Matche
    {
        // Mise à jour des informations du match
        $matche->update([
            'score_equipe1' => $data['score_equipe1'],
            'score_equipe2' => $data['score_equipe2'],
            'buteurs' => $data['buteurs'] ?? [],
            'passeurs' => $data['passeurs'] ?? [],
            'cartons' => $data['cartons'] ?? [],
            'homme_du_matche' => $data['homme_du_matche'] ?? null,
            'statut' => 'termine',
        ]);

        // Mise à jour des classements des équipes
        $this->updateStandings($matche);

        // Notification des résultats
        $this->notificationService->notifyMatchResult($matche);

        return $matche->fresh(['equipe1', 'equipe2', 'competition', 'hommeDuMatch']);
    }

    // Mettre à jour les classements des équipes après chaque match
    protected function updateStandings(Matche $matche): void
    {
        $this->updateTeamStanding($matche->equipe1_id, $matche);
        $this->updateTeamStanding($matche->equipe2_id, $matche);
    }

    // Mettre à jour le classement d'une équipe
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

    // Supprimer un match
    public function deleteMatche(Matche $matche): bool
    {
        return DB::transaction(function () use ($matche) {
            return $matche->delete();
        });
    }
}

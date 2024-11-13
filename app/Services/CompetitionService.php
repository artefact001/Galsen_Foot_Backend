<?php
namespace App\Services;

use App\Models\Competition;
use Illuminate\Support\Facades\Log;

class CompetitionService
{
    // Check if a team is registered in a competition
    public function estEquipeDansCompetition(int $competitionId, int $equipeId): bool
    {
        try {
            $competition = Competition::findOrFail($competitionId);
            return $competition->teams()->where('equipe_id', $equipeId)->exists();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification de l\'inscription de l\'équipe : ' . $e->getMessage());
            return false;
        }
    }

    // Create a new competition
    public function creerCompetition(array $data): Competition
    {
        try {
            return Competition::create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la compétition : ' . $e->getMessage());
            throw new \Exception('Erreur lors de la création de la compétition. Détails : ' . $e->getMessage(), 500);
        }
    }

    // Update an existing competition
    public function mettreAJourCompetition(Competition $competition, array $data): Competition
    {
        try {
            $competition->update($data);
            return $competition;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la compétition : ' . $e->getMessage());
            throw new \Exception('Erreur lors de la mise à jour de la compétition.');
        }
    }

    // Delete a competition
    public function supprimerCompetition(Competition $competition): void
    {
        try {
            $competition->delete();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la compétition : ' . $e->getMessage());
            throw new \Exception('Erreur lors de la suppression de la compétition.');
        }
    }

    // Get a competition by its ID
    public function recupererCompetitionParId(int $id): Competition
    {
        return Competition::findOrFail($id);
    }

    // Get all competitions
    public function recupererToutesLesCompetitions(): \Illuminate\Database\Eloquent\Collection
    {
        return Competition::all();
    }
}

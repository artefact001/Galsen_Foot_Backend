<?php

namespace App\Services;

use App\Models\Competition;
use Illuminate\Support\Facades\Log;

class CompetitionService
{
    /**
     * Créer une nouvelle compétition.
     *
     * @param array $data Les données de la nouvelle compétition
     * @return Competition
     * @throws \Exception
     */
    public function creerCompetition(array $data): Competition
    {
        try {
            return Competition::create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'une compétition : ' . $e->getMessage());
            throw new \Exception('Erreur lors de la création de la compétition.');
        }
    }

    /**
     * Mettre à jour une compétition existante.
     *
     * @param Competition $competition La compétition à mettre à jour
     * @param array $data Les nouvelles données
     * @return Competition
     * @throws \Exception
     */
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

    /**
     * Supprimer une compétition.
     *
     * @param Competition $competition La compétition à supprimer
     * @return void
     * @throws \Exception
     */
    public function supprimerCompetition(Competition $competition): void
    {
        try {
            $competition->delete();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la compétition : ' . $e->getMessage());
            throw new \Exception('Erreur lors de la suppression de la compétition.');
        }
    }

    /**
     * Récupérer une compétition par son identifiant.
     *
     * @param int $id L'identifiant de la compétition
     * @return Competition
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function recupererCompetitionParId(int $id): Competition
    {
        return Competition::findOrFail($id);
    }

    /**
     * Récupérer toutes les compétitions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recupererToutesLesCompetitions(): \Illuminate\Database\Eloquent\Collection
    {
        return Competition::all();
    }
}

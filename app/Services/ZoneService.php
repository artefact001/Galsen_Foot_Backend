<?php

namespace App\Services;

use App\Models\Zone;
use Illuminate\Support\Facades\Log;

class ZoneService
{
    // Retrieve all zones
    public function getAllZones()
    {
        try {
            return Zone::all();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des zones : ' . $e->getMessage());
            throw new \Exception('Erreur lors de la récupération des zones.');
        }
    }

    // Retrieve details of a specific zone by ID
    public function getZoneById(int $id)
    {
        try {
            return Zone::with("equipes")->findOrFail($id);
        } catch (\Exception $e) {
            Log::error("Zone non trouvée avec l'ID $id : " . $e->getMessage());
            throw new \Exception('Zone non trouvée.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ZoneService;
use Illuminate\Http\JsonResponse;

class ZoneController extends Controller
{
    protected $zoneService;

    public function __construct(ZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }

    /**
     * List all zones.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $zones = $this->zoneService->getAllZones();
            return response()->json($zones, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des zones'], 500);
        }
    }

    /**
     * Display details of a specific zone.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $zone = $this->zoneService->getZoneById($id);
            return response()->json($zone, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Zone non trouvée'], 404);
        }
    }
}

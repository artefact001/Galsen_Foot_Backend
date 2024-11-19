<?php

namespace App\Http\Controllers;

use App\Http\Requests\TirageRequest;
use App\Services\TirageService;
use App\Models\Equipe;
use App\Models\Tirage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TirageController extends Controller
{

    protected $tirageService;

    public function __construct(TirageService $tirageService)
    {
        $this->tirageService = $tirageService;
    }

    /**
     * Display a list of all tirages or filter by competition ID.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $competitionId = $request->query('competition_id');
        $tirages = $this->tirageService->obtenirTousLesTirages($competitionId);

        return response()->json($tirages);
    }

    /**
     * Show a specific tirage by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $tirage = Tirage::find($id);

        if (!$tirage) {
            return response()->json(['message' => 'Tirage not found'], 404);
        }

        return response()->json($tirage);
    }
/**
 * Lancer un tirage de poules.
 *
 * @param TirageRequest $request
 * @return JsonResponse
 */
public function lancerTirage(TirageRequest $request): JsonResponse
{
    // Récupérer les équipes de la compétition dans la zone sélectionnée
    $equipes = Equipe::where('zone_id', $request->zone_id)->pluck('id')->toArray();

    // Vérifier s'il y a des équipes dans la zone
    if (empty($equipes)) {
        return response()->json(['message' => 'Aucune équipe trouvée dans cette zone.'], 404);
    }

    // Générer les poules
    $poules = $this->tirageService->genererPoules($request->nombre_poules, $equipes);

    // Créer le tirage avec les poules générées
    $tirage = $this->tirageService->creerTirage([
        'phase' => $request->phase,
        'competition_id' => $request->competition_id,
        'poules' => json_encode($poules),
    ]);

    return response()->json($tirage, 201);
}


   }

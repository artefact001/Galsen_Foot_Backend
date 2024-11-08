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
        $equipes = Equipe::where('competition_id', $request->competition_id)->pluck('id')->toArray();

        $poules = $this->tirageService->genererPoules($request->nombre_poules, $equipes);

        $tirage = $this->tirageService->creerTirage([
            'phase' => $request->phase,
            'competition_id' => $request->competition_id,
            'poules' => json_encode($poules),
            'nombre_poules' => $request->nombre_poules,
        ]);

        return response()->json($tirage, 201);
    }
}

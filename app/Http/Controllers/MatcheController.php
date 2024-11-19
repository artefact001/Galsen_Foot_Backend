<?php
namespace App\Http\Controllers;

use App\Http\Requests\MatcheRequest;
use App\Models\Matche;
use App\Services\MatcheService;
use Illuminate\Http\JsonResponse;

class MatcheController extends Controller
{
    protected MatcheService $matcheService;

    public function __construct(MatcheService $matcheService)
    {
        $this->matcheService = $matcheService;
    }

    // Récupérer tous les matchs
    public function index(): JsonResponse
    {
        $matches = $this->matcheService->getAllMatches();
        return response()->json($matches);
    }

    // Créer un nouveau match
    public function store(MatcheRequest $request): JsonResponse
    {
        $matche = $this->matcheService->createMatche($request->all());
        return response()->json($matche, 201);
    }

    // Récupérer un match par son ID
    public function show(int $id): JsonResponse
    {
        $matche = $this->matcheService->getMatchById($id);
        return response()->json($matche);
    }

    // Mettre à jour un match
    public function update(MatcheRequest $request, int $id): JsonResponse
    {
        $matche = $this->matcheService->getMatchById($id);
        $updatedMatche = $this->matcheService->updateMatche($matche, $request->validated());
        return response()->json($updatedMatche);
    }

    // Supprimer un match
    public function destroy(int $id): JsonResponse
    {
        $matche = $this->matcheService->getMatchById($id);
        $this->matcheService->deleteMatche($matche);
        return response()->json(null, 204);
    }
}

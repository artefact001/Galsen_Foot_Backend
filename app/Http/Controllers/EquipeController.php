<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipeRequest;  // Importer EquipeRequest pour la validation
use App\Services\EquipeService;
use Illuminate\Http\JsonResponse;

class EquipeController extends Controller
{
    private $equipeService;

    public function __construct(EquipeService $equipeService)
    {
        $this->equipeService = $equipeService;
    }

    /**
     * Afficher la liste de toutes les équipes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $equipes = $this->equipeService->recupererToutesLesEquipes();
        return response()->json($equipes);
    }

    /**
     * Créer une nouvelle équipe.
     *
     * @param EquipeRequest $request  // Utilisation de EquipeRequest
     * @return JsonResponse
     */
    public function store(EquipeRequest $request): JsonResponse
    {
        // Utiliser validated() pour récupérer les données validées
        $data = $request->validated();

        // Créer l'équipe avec les données validées
        $equipe = $this->equipeService->creerEquipe($data);

        return response()->json($equipe, 201);
    }

    /**
     * Afficher une équipe spécifique.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $equipe = $this->equipeService->recupererEquipeParId($id);
        return response()->json($equipe);
    }

    /**
     * Mettre à jour une équipe existante.
     *
     * @param EquipeRequest $request  // Utilisation de EquipeRequest
     * @param int $id
     * @return JsonResponse
     */
    public function update(EquipeRequest $request, int $id): JsonResponse
    {
        // Utiliser validated() pour récupérer les données validées
        $data = $request->validated();

        // Récupérer l'équipe et la mettre à jour
        $equipe = $this->equipeService->recupererEquipeParId($id);
        $equipe = $this->equipeService->mettreAJourEquipe($equipe, $data);

        return response()->json($equipe);
    }

    /**
     * Supprimer une équipe.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $equipe = $this->equipeService->recupererEquipeParId($id);
        $this->equipeService->supprimerEquipe($equipe);

        return response()->json(['message' => 'Équipe supprimée avec succès.'], 204);
    }
}

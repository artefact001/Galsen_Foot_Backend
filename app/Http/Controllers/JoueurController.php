<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoueurRequest;
use App\Services\JoueurService;
use Illuminate\Http\JsonResponse;

use  App\Http\Requests\ValidatoreRequest;

class JoueurController extends Controller
{
    protected $joueurService;

    public function __construct(JoueurService $joueurService)
    {
        $this->joueurService = $joueurService;
    }

    /**
     * Récupérer tous les joueurs.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $joueurs = $this->joueurService->recupererTousLesJoueurs();
        return response()->json($joueurs, 200);
    }

    /**
     * Créer un nouveau joueur.
     *
     * @param JoueurRequest $request
     * @return JsonResponse
     */
    public function store(JoueurRequest $request): JsonResponse  // Utilisation de JoueurRequest
    {
        // Utilisation de validated() pour récupérer les données validées
        $joueur = $this->joueurService->creerJoueur($request->all());

        return response()->json($joueur, 201);
    }

    /**
     * Afficher un joueur spécifique.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $joueur = $this->joueurService->recupererJoueurParId($id);
        return response()->json($joueur, 200);
    }

    /**
     * Mettre à jour un joueur existant.
     *
     * @param JoueurRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(JoueurRequest $request, int $id): JsonResponse
    {
        // Récupérer le joueur par son ID
        $joueur = $this->joueurService->recupererJoueurParId($id);

        // Mettre à jour le joueur avec les données validées
        $joueur = $this->joueurService->mettreAJourJoueur($joueur, $request->validated());

        return response()->json($joueur, 200);
    }

    /**
     * Supprimer un joueur.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Récupérer le joueur à supprimer
        $joueur = $this->joueurService->recupererJoueurParId($id);

        // Supprimer le joueur
        $this->joueurService->supprimerJoueur($joueur);

        return response()->json(null, 204);
    }
}

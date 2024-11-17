<?php
namespace App\Http\Controllers;

use App\Http\Requests\CompetitionRequest;
use App\Models\Competition;
use App\Services\CompetitionService;
use Illuminate\Http\JsonResponse;

class CompetitionController extends Controller
{
    protected $competitionService;

    public function __construct(CompetitionService $competitionService)
    {
        $this->competitionService = $competitionService;
    }

    /**
     * Afficher la liste des compétitions.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $competitions = $this->competitionService->recupererToutesLesCompetitions();
        return response()->json($competitions, 200);
    }

    /**
     * Créer une nouvelle compétition.
     *
     * @param CompetitionRequest $request
     * @return JsonResponse
     */
    public function store(CompetitionRequest $request): JsonResponse
    {


        $competition = $this->competitionService->creerCompetition($request->all());

            // Log::error('Custom error message', ['data' => $request->all()]);

        return response()->json($competition, 201);
    }

    /**
     * Afficher une compétition spécifique.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $competition = $this->competitionService->recupererCompetitionParId($id);
            return response()->json($competition, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Competition not found'], 404);
        }
    }

    /**
     * Mettre à jour une compétition existante.
     *
     * @param CompetitionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CompetitionRequest $request, int $id): JsonResponse
    {
        try {
            $competition = $this->competitionService->recupererCompetitionParId($id);
            $competition = $this->competitionService->mettreAJourCompetition($competition, $request->validated());
            return response()->json($competition, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Competition not found'], 404);
        }
    }

    /**
     * Supprimer une compétition.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $competition = $this->competitionService->recupererCompetitionParId($id);
            $this->competitionService->supprimerCompetition($competition);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Competition not found'], 404);
        }
    }


    /**
 * Afficher les détails d'une compétition spécifique.
 *
 * @param int $id
 * @return JsonResponse
 */
public function showDetails(int $id): JsonResponse
{
    try {
        $competition = $this->competitionService->recupererCompetitionParId($id);
        return response()->json($competition, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Compétition non trouvée'], 404);
    }
}


 public function EquipeParticiper(int $competitionId, int $teamId): JsonResponse
    {
        $this->competitionService->participerEquipe($competitionId, $teamId);
        return response()->json(['message' => 'Equipe inscrite avec successe'], 200);
    }



    /**
     * Vérifier si une équipe est inscrite à une compétition.
     *
     * @param int $competitionId
     * @param int $teamId
     * @return JsonResponse
     */
    public function isEquipeInCompetition(int $competitionId, int $teamId): JsonResponse
    {
        $isInCompetition = $this->competitionService->estEquipeDansCompetition($competitionId, $teamId);

        if ($isInCompetition) {
            return response()->json(['isInCompetition' => true], 200);
        } else {
            return response()->json(['isInCompetition' => false], 200);
        }
    }

   //ajouter un equipe dans une competition pour avoir la liste des equipe qui participent a cette competition


 public function ajouterEquipeACompetition()
 {
     $competitionService = new CompetitionService();

     try {
         $result = $competitionService->ajouterEquipeACompetition(1, 10); // Compétition ID: 1, Équipe ID: 10
         if ($result) {
             echo "L'équipe a été inscrite avec succès.";
         } else {
             echo "L'équipe est déjà inscrite.";
         }
     } catch (\Exception $e) {
         echo "Erreur : " . $e->getMessage();
     }
 }
}

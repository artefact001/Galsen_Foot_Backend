<?php


namespace App\Http\Controllers;
use App\Http\Requests\ValidationRequest;

use App\Services\PointService;
use Illuminate\Http\JsonResponse;

class PointController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    // Afficher le classement global
    public function rankings(): JsonResponse
    {
        $rankings = $this->pointService->getRankings();

        return response()->json($rankings);
    }

    // Afficher les points d'une équipe donnée
    public function teamPoints($joueurId): JsonResponse
    {
        $points = $this->pointService->getTeamPoints($joueurId);

        return response()->json([
            'joueur_id' => $joueurId,
            'total_points' => $points,
        ]);
    }
}


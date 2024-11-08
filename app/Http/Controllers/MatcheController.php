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

    public function index(): JsonResponse
    {
        $matches = $this->matcheService->getAllMatches();
        return response()->json($matches);
    }

    public function store(MatcheRequest $request): JsonResponse
    {
        $matche = $this->matcheService->createMatche($request->all());
        return response()->json($matche, 201);
    }

    public function show(int $id): JsonResponse
    {
        $matche = $this->matcheService->getMatchById($id);
        return response()->json($matche);
    }

    public function update(MatcheRequest $request, int $id): JsonResponse
    {
        $matche = $this->matcheService->getMatchById($id);
        $updatedMatche = $this->matcheService->updateMatche($matche, $request->validated());
        return response()->json($updatedMatche);
    }

    public function destroy(int $id): JsonResponse
    {
        $matche = $this->matcheService->getMatchById($id);
        $this->matcheService->deleteMatche($matche);
        return response()->json(null, 204);
    }
}

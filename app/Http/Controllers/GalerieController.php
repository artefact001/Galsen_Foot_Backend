<?php

namespace App\Http\Controllers;

use App\Services\GalerieService;
use Illuminate\Http\Request;

class GalerieController extends Controller
{
    protected $galerieService;

    public function __construct(GalerieService $galerieService)
    {
        $this->galerieService = $galerieService;
    }

    /**
     * Display a listing of the galerie records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $galeries = $this->galerieService->getAll();
        return response()->json($galeries);
    }

    /**
     * Store a newly created galerie record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
        ]);

        $galerie = $this->galerieService->create($request->only('image'));
        return response()->json($galerie, 201);
    }

    /**
     * Display the specified galerie record.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $galerie = $this->galerieService->findById($id);

        if (!$galerie) {
            return response()->json(['message' => 'Galerie not found'], 404);
        }

        return response()->json($galerie);
    }

    /**
     * Update the specified galerie record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image if provided
        ]);

        $data = $request->only(['image']);
        $galerie = $this->galerieService->update($id, $data);

        if (!$galerie) {
            return response()->json(['message' => 'Galerie not found'], 404);
        }

        return response()->json($galerie);
    }

    /**
     * Remove the specified galerie record from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $deleted = $this->galerieService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Galerie not found or could not be deleted'], 404);
        }

        return response()->json(['message' => 'Galerie deleted successfully']);
    }
}

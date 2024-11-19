<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;

use  App\Http\Requests\ValidatoreRequest;

class ArticleController extends Controller
{
    protected $ArticleService;

    public function __construct(ArticleService $ArticleService)
    {
        $this->ArticleService = $ArticleService;
    }


    /**
     * Récupérer tous les Articles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $Articles = $this->ArticleService->recupererTousLesArticles();
        return response()->json($Articles, 200);
    }

    /**
     * Créer un nouveau Article.
     *
     * @param ArticleRequest $request
     * @return JsonResponse
     */
    public function store(ArticleRequest $request): JsonResponse  // Utilisation de ArticleRequest
    {
        // Utilisation de validated() pour récupérer les données validées
        $Article = $this->ArticleService->creerArticle($request->all());

        return response()->json($Article, 201);
    }

    /**
     * Afficher un Article spécifique.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $Article = $this->ArticleService->recupererArticleParId($id);
        return response()->json($Article, 200);
    }

    /**
     * Mettre à jour un Article existant.
     *
     * @param ArticleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ArticleRequest $request, int $id): JsonResponse
    {
        // Récupérer le Article par son ID
        $Article = $this->ArticleService->recupererArticleParId($id);

        // Mettre à jour le Article avec les données validées
        $Article = $this->ArticleService->mettreAJourArticle($Article, $request->validated());

        return response()->json($Article, 200);
    }

    /**
     * Supprimer un Article.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Récupérer le Article à supprimer
        $Article = $this->ArticleService->recupererArticleParId($id);

        // Supprimer le Article
        $this->ArticleService->supprimerArticle($Article);

        return response()->json(null, 204);
    }
}

<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function recupererTousLesArticles()
    {
        return Article::all();
    }

    public function creerArticle(array $data)
    {
        return Article::create($data);
    }

    public function recupererArticleParId(int $id)
    {
        return Article::findOrFail($id);
    }

    public function mettreAJourArticle(Article $Article, array $data)
    {
        $Article->update($data);
        return $Article;
    }

    public function supprimerArticle(Article $Article)
    {
        $Article->delete();
    }
}

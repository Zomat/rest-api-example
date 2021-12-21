<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * @group Article
 */

class ArticleController extends Controller
{
    /**
     * Get all articles.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Article::all());
    }

    /**
     * Get article by id.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article)
    {
        return response()->json($article, 200);
    }

    /**
     * Store new article.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    /**
     * Update article.
     *
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return response()->json($article, 200);
    }

    /**
     * Delete article.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function delete(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(SearchArticlesRequest $request)
    {
        $query = Article::query();
        $filter = new ArticleFilter();
        $articles = $filter->apply($query, $request)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return response()->json([
            'success' => true,
            'message' => 'Articles retrieved successfully.',
            'data'    => ArticleResource::collection($articles),
            'meta'    => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'per_page'     => $articles->perPage(),
                'total'        => $articles->total(),
            ]
        ]);
    }
}

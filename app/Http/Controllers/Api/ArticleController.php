<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(SearchArticlesRequest $request)
    {
        $articles = Article::query()
            ->when($request->input('source'), fn($q) => $q->where('source', $request->source))
            ->when($request->input('category'), fn($q) => $q->where('category', $request->category))
            ->when($request->input('author'), fn($q) => $q->where('author', $request->author))
            ->when($request->input('q'), fn($q) => $q->where('title', 'like', "%{$request->q}%"))
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return ArticleResource::collection($articles);
    }
}

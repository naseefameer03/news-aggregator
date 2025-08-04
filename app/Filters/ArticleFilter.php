<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleFilter
{
    public function apply(Builder $query, Request $request): Builder
    {
        // Use parameter binding for LIKE queries to prevent SQL injection
        return $query
            ->when($request->filled('title'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . addcslashes($request->input('title'), '%_\\') . '%');
            })
            ->when($request->filled('source'), function ($q) use ($request) {
                $q->where('source', 'like', '%' . addcslashes($request->input('source'), '%_\\') . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category', 'like', '%' . addcslashes($request->input('category'), '%_\\') . '%');
            })
            ->when($request->filled('author'), function ($q) use ($request) {
                $q->where('author', 'like', '%' . addcslashes($request->input('author'), '%_\\') . '%');
            })
            ->when($request->filled('date'), function ($q) use ($request) {
                $q->whereDate('published_at', $request->input('date'));
            });
    }
}

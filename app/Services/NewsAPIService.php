<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;

class NewsAPIService implements NewsSourceInterface
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.newsapi.url');
        $this->apiKey  = config('services.newsapi.key');
    }

    public function fetchArticles(): array
    {
        $response = Http::get("{$this->baseUrl}/top-headlines", [
            'apiKey' => $this->apiKey,
            'country' => 'us',
            'pageSize' => 20,
        ]);

        if (!$response->successful()) {
            return [];
        }

        return collect($response->json('articles'))->map(function ($item) {
            return [
                'title'        => $item['title'] ?? null,
                'description'  => $item['description'] ?? null,
                'content'      => $item['content'] ?? null,
                'author'       => $item['author'] ?? 'Unknown',
                'source'       => $item['source']['name'] ?? 'NewsAPI',
                'category'     => null, // Optional, if available
                'url'          => $item['url'] ?? null,
                'image_url'    => $item['urlToImage'] ?? null,
                'published_at' => $item['publishedAt'] ?? now(),
            ];
        })->toArray();
    }
}

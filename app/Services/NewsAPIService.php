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
            'pageSize' => 10,
        ]);

        if (!$response->successful()) {
            return [];
        }

        return collect($response->json('articles'))->map(function ($item) {
            return [
                'title'        => $item['title'] ?? null,
                'content'      => $item['description'] ?? null,
                'source'       => $item['source']['name'] ?? 'NewsAPI',
                'category'     => NULL,
                'author'       => $item['author'] ?? 'Unknown',
                'url'          => $item['url'] ?? null,
                'published_at' => isset($item['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['publishedAt'])) : now(),
                'api_source'   => 'NewsAPI',
            ];
        })->toArray();
    }
}

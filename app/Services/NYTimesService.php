<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;

class NYTimesService implements NewsSourceInterface
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.nytimes.url');
        $this->apiKey  = config('services.nytimes.key');
    }

    public function fetchArticles(): array
    {
        $endpoint = "{$this->baseUrl}/home.json";

        $response = Http::get($endpoint, [
            'api-key' => $this->apiKey
        ]);

        if (!$response->successful()) {
            return [];
        }

        $articles = $response->json('results');
        $articles = array_slice($articles, 0, 10);

        return collect($articles)->map(function ($item) {
            return [
                'title'        => $item['title'] ?? null,
                'content'      => $item['abstract'] ?? null,
                'source'       => 'NYTimes',
                'category'     => $item['section'] ?? null,
                'author'       => $item['byline'] ?? 'Unknown',
                'url'          => $item['url'] ?? null,
                'published_at' => isset($item['published_date']) ? date('Y-m-d H:i:s', strtotime($item['published_date'])) : now(),
                'api_source'   => 'NYTimes',
            ];
        })->toArray();
    }
}

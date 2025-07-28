<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;

class OpenNewsService implements NewsSourceInterface
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.opennews.url');
        $this->apiKey  = config('services.opennews.key');
    }

    public function fetchArticles(): array
    {
        $response = Http::get("{$this->baseUrl}/news", [
            'apiKey' => $this->apiKey,
            'limit'  => 20,
        ]);

        if (!$response->successful()) {
            return [];
        }

        return collect($response->json('data'))->map(function ($item) {
            return [
                'title'        => $item['headline'] ?? null,
                'description'  => $item['summary'] ?? null,
                'content'      => $item['body'] ?? null,
                'author'       => $item['byline'] ?? 'Unknown',
                'source'       => $item['source'] ?? 'OpenNews',
                'category'     => $item['category'] ?? null,
                'url'          => $item['url'] ?? null,
                'image_url'    => $item['image'] ?? null,
                'published_at' => $item['published_at'] ?? now(),
            ];
        })->toArray();
    }
}

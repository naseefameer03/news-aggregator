<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;

class TheGuardianAPIService implements NewsSourceInterface
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.guardian.url');
        $this->apiKey  = config('services.guardian.key');
    }

    public function fetchArticles(array $params = []): array
    {
        $cacheKey = 'guardian_articles_' . md5(json_encode($params));
        return cache()->remember($cacheKey, 300, function () use ($params) {
            $query = [
                'api-key' => $this->apiKey,
                'show-fields' => 'bodyText,byline',
                'page-size' => $params['pageSize'] ?? 10,
                'order-by' => 'newest',
            ];
            if (!empty($params['section'])) {
                $query['section'] = $params['section'];
            }
            $response = Http::get("{$this->baseUrl}/search", $query);
            if (!$response->successful()) {
                return [];
            }
            return collect($response->json('response.results'))->map(function ($item) {
                return [
                    'title'        => $item['webTitle'] ?? null,
                    'content'      => \Illuminate\Support\Str::limit(strip_tags($item['fields']['bodyText'] ?? ''), 250),
                    'source'       => $item['publication'] ?? 'The Guardian',
                    'category'     => $item['sectionName'] ?? null,
                    'author'       => $item['fields']['byline'] ?? 'Unknown',
                    'url'          => $item['webUrl'] ?? null,
                    'published_at' => isset($item['webPublicationDate']) ? date('Y-m-d H:i:s', strtotime($item['webPublicationDate'])) : now(),
                    'api_source'   => 'TheGuardian',
                ];
            })->toArray();
        });
    }
}

<?php

namespace App\Jobs;

use App\Repositories\ArticleRepository;
use App\Services\NewsAPIService;
use App\Services\NYTimesService;
use App\Services\TheGuardianAPIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class FetchArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $sources;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->sources = [
            NewsAPIService::class,
            NYTimesService::class,
            TheGuardianAPIService::class,
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $repository = App::make(ArticleRepository::class);

        foreach ($this->sources as $serviceClass) {
            /** @var NewsSourceInterface $service */
            $service = App::make($serviceClass);

            try {
                $articles = $service->fetchArticles();

                foreach ($articles as $articleData) {
                    $repository->storeOrUpdate($articleData);
                }

                Log::info("Fetched and stored articles from " . class_basename($serviceClass));
            } catch (\Exception $e) {
                Log::error("Failed to fetch articles from " . class_basename($serviceClass) . ": " . $e->getMessage());
            }
        }
    }
}

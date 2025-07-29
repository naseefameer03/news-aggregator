<?php

namespace App\Console\Commands;

use App\Jobs\FetchArticlesJob;
use Illuminate\Console\Command;

class FetchArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from all configured news sources and store them locally.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        FetchArticlesJob::dispatch();
        $this->info('Article fetch job dispatched successfully.');
    }
}

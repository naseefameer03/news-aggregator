<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    /**
     * Store or update an article to avoid duplicates.
     */
    public function storeOrUpdate(array $data): void
    {
        Article::updateOrCreate(
            ['url' => $data['url']], // unique identifier
            $data
        );
    }
}

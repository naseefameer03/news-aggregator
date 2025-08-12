<?php

namespace App\Repositories;


use App\Models\Article;
use App\DTO\ArticleDTO;

class ArticleRepository
{
    /**
     * Store or update an article to avoid duplicates.
     */
    public function storeOrUpdate(ArticleDTO $dto): void
    {
        Article::updateOrCreate(
            ['url' => $dto->url], // unique identifier
            $dto->toArray()
        );
    }
}

<?php

namespace App\Services\Contracts;

interface NewsSourceInterface
{
    /**
     * Fetch articles from a source.
     *
     * @return array An array of standardized article data
     */
    public function fetchArticles(): array;
}

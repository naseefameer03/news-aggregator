<?php

namespace App\DTO;

class ArticleDTO
{
    public string $title;
    public ?string $content;
    public string $source;
    public ?string $category;
    public string $author;
    public ?string $url;
    public ?string $published_at;
    public ?string $api_source;

    public function __construct(array $data)
    {
        $this->title        = $data['title'] ?? '';
        $this->content      = $data['content'] ?? null;
        $this->source       = $data['source'] ?? '';
        $this->category     = $data['category'] ?? null;
        $this->author       = $data['author'] ?? '';
        $this->url          = $data['url'] ?? null;
        $this->published_at = $data['published_at'] ?? null;
        $this->api_source   = $data['api_source'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'title'        => $this->title,
            'content'      => $this->content,
            'source'       => $this->source,
            'category'     => $this->category,
            'author'       => $this->author,
            'url'          => $this->url,
            'published_at' => $this->published_at,
            'api_source'   => $this->api_source,
        ];
    }
}

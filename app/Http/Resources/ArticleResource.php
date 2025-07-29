<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->uuid,
            'title'        => $this->title,
            'content'      => $this->content,
            'source'       => $this->source,
            'category'     => $this->category,
            'author'       => $this->author,
            'url'          => $this->url,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at)->toIso8601String() : null,
        ];
    }
}

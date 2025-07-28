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
            'id'           => $this->id,
            'title'        => $this->title,
            'content'      => $this->content,
            'source'       => $this->source,
            'category'     => $this->category,
            'author'       => $this->author,
            'url'          => $this->url,
            'image_url'    => $this->image_url,
            'published_at' => $this->published_at ? $this->published_at->toIso8601String() : null,
        ];
    }
}

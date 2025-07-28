<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'source',
        'category',
        'author',
        'url',
        'published_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $article->uuid = Str::orderedUuid();
        });
    }
}

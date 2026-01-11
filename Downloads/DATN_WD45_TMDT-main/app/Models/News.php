<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'excerpt',
        'author_id',
        'status',
        'views',
    ];

    protected $casts = [
        'status' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Get the author of the news.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }
}

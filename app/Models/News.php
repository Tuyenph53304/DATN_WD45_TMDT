<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image_url',
        'author_id',
        'is_published',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the author of the news
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope to get published news
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    /**
     * Scope to order by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * Increment views count
     */
    public function incrementViewCount()
    {
        $this->increment('views_count');
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}

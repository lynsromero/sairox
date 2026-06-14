<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_author',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'slug',
        'post_type',
        'comment_count',
        'thumbnail',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'post_author');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Term::class, 'termable', 'term_relationships')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'category'));
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Term::class, 'termable', 'term_relationships')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'post_tag'));
    }
}

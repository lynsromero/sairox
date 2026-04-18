<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "post_author",
        "post_content",
        "post_title",
        "post_excerpt",
        "post_status",
        "comment_status",
        "slug",
        "post_type",
        "comment_count",
        "thumbnail"
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'post_author');
    }
}

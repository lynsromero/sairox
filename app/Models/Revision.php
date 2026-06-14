<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Revision extends Model
{
    protected $fillable = [
        'revisionable_id',
        'revisionable_type',
        'user_id',
        'data',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

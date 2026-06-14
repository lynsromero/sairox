<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Widget extends Model
{
    protected $fillable = ['widget_area_id', 'type', 'settings', 'order'];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'order' => 'integer',
        ];
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(WidgetArea::class, 'widget_area_id');
    }
}

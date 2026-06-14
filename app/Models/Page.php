<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Page extends Post
{
    protected $table = 'posts';

    protected $attributes = [
        'post_type' => 'page',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('post_type_page', function (Builder $builder) {
            $builder->where('post_type', 'page');
        });

        static::creating(function (Page $page) {
            $page->post_type = 'page';
        });
    }

    public static function getAvailableTemplates(): array
    {
        return [
            'default' => 'Default Template',
            'full_width' => 'Full Width',
            'sidebar' => 'With Sidebar',
            'blank' => 'Blank Page',
            'landing' => 'Landing Page',
        ];
    }
}

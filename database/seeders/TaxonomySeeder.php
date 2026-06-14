<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        Taxonomy::create([
            'taxonomy' => 'category',
            'description' => 'Post categories',
            'slug' => 'category',
            'hierarchical' => true,
        ]);

        Taxonomy::create([
            'taxonomy' => 'post_tag',
            'description' => 'Post tags',
            'slug' => 'tag',
            'hierarchical' => false,
        ]);
    }
}

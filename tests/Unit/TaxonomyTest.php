<?php

namespace Tests\Unit;

use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_taxonomy(): void
    {
        $tax = Taxonomy::create([
            'taxonomy' => 'category',
            'description' => 'Post categories',
            'slug' => 'category',
            'hierarchical' => true,
        ]);

        $this->assertDatabaseHas('taxonomies', ['taxonomy' => 'category']);
        $this->assertTrue($tax->hierarchical);
    }

    public function test_can_create_term(): void
    {
        $tax = Taxonomy::create([
            'taxonomy' => 'post_tag',
            'description' => 'Post tags',
            'slug' => 'tag',
        ]);

        $term = Term::create([
            'taxonomy_id' => $tax->id,
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $this->assertDatabaseHas('terms', ['name' => 'Laravel', 'slug' => 'laravel']);
        $this->assertEquals($tax->id, $term->taxonomy->id);
    }

    public function test_term_belongs_to_taxonomy(): void
    {
        $tax = Taxonomy::create(['taxonomy' => 'category', 'slug' => 'cat']);
        $term = Term::create(['taxonomy_id' => $tax->id, 'name' => 'News', 'slug' => 'news']);

        $this->assertInstanceOf(Taxonomy::class, $term->taxonomy);
    }

    public function test_taxonomy_has_many_terms(): void
    {
        $tax = Taxonomy::create(['taxonomy' => 'category', 'slug' => 'cat']);
        Term::create(['taxonomy_id' => $tax->id, 'name' => 'A', 'slug' => 'a']);
        Term::create(['taxonomy_id' => $tax->id, 'name' => 'B', 'slug' => 'b']);

        $this->assertCount(2, $tax->terms);
    }
}

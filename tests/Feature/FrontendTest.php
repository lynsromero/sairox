<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Taxonomy::create(['taxonomy' => 'category', 'description' => '', 'slug' => 'category', 'hierarchical' => true]);
        Taxonomy::create(['taxonomy' => 'post_tag', 'description' => '', 'slug' => 'tag', 'hierarchical' => false]);
    }

    public function test_homepage_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_homepage_lists_posts(): void
    {
        Post::create(['post_title' => 'Front Post', 'post_content' => 'Content', 'slug' => 'front-post', 'post_status' => 'publish']);

        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('Front Post');
    }

    public function test_single_post_page(): void
    {
        Post::create(['post_title' => 'Single Post', 'post_content' => '<p>Hello</p>', 'slug' => 'single-post', 'post_status' => 'publish']);

        $response = $this->get('/posts/single-post');
        $response->assertStatus(200)
            ->assertSee('Single Post')
            ->assertSee('Hello');
    }

    public function test_single_post_404_for_missing(): void
    {
        $response = $this->get('/posts/nonexistent');
        $response->assertStatus(404);
    }

    public function test_page_route(): void
    {
        Page::create(['post_title' => 'About Us', 'post_content' => 'About content', 'slug' => 'about', 'post_status' => 'publish']);

        $response = $this->get('/about');
        $response->assertStatus(200)
            ->assertSee('About Us');
    }

    public function test_category_page(): void
    {
        $cat = Term::create([
            'taxonomy_id' => Taxonomy::where('taxonomy', 'category')->first()->id,
            'name' => 'News',
            'slug' => 'news',
        ]);

        $response = $this->get('/categories/news');
        $response->assertStatus(200)
            ->assertSee('News');
    }

    public function test_tag_page(): void
    {
        $tag = Term::create([
            'taxonomy_id' => Taxonomy::where('taxonomy', 'post_tag')->first()->id,
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $response = $this->get('/tags/laravel');
        $response->assertStatus(200)
            ->assertSee('Laravel');
    }
}

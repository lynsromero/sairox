<?php

namespace Tests\Feature\Api;

use App\Models\MediaFile;
use App\Models\Option;
use App\Models\Page;
use App\Models\Post;
use App\Models\Taxonomy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiKey = 'test-api-key-123';

    protected function setUp(): void
    {
        parent::setUp();
        Option::create(['option_name' => 'api_key', 'option_value' => $this->apiKey]);
        $this->seedTaxonomies();
    }

    protected function seedTaxonomies(): void
    {
        Taxonomy::create(['taxonomy' => 'category', 'description' => '', 'slug' => 'category', 'hierarchical' => true]);
        Taxonomy::create(['taxonomy' => 'post_tag', 'description' => '', 'slug' => 'tag', 'hierarchical' => false]);
    }

    public function test_api_requires_key(): void
    {
        $response = $this->getJson('/api/posts');
        $response->assertStatus(401);
    }

    public function test_api_returns_posts_with_valid_key(): void
    {
        Post::create(['post_title' => 'API Post', 'post_content' => 'C1', 'slug' => 'api-post', 'post_status' => 'publish']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/posts');
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta', 'links'])
            ->assertJsonCount(1, 'data');
    }

    public function test_api_returns_single_post(): void
    {
        $post = Post::create(['post_title' => 'Single', 'post_content' => 'C1', 'slug' => 'single', 'post_status' => 'publish']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson("/api/posts/{$post->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.post_title', 'Single');
    }

    public function test_api_returns_post_by_slug(): void
    {
        Post::create(['post_title' => 'By Slug', 'post_content' => 'C1', 'slug' => 'by-slug', 'post_status' => 'publish']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/posts/slug/by-slug');
        $response->assertStatus(200)
            ->assertJsonPath('data.post_title', 'By Slug');
    }

    public function test_api_returns_404_for_missing_post(): void
    {
        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/posts/99999');
        $response->assertStatus(404);
    }

    public function test_api_returns_pages(): void
    {
        Page::create(['post_title' => 'API Page', 'post_content' => 'C1', 'slug' => 'api-page', 'post_status' => 'publish']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/pages');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_api_returns_categories(): void
    {
        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/categories');
        $response->assertStatus(200);
    }

    public function test_api_returns_tags(): void
    {
        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/tags');
        $response->assertStatus(200);
    }

    public function test_api_returns_media(): void
    {
        MediaFile::create(['title' => 'Test', 'file_path' => 'test.jpg', 'file_type' => 'image/jpeg']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/media');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_api_returns_settings(): void
    {
        Option::create(['option_name' => 'title', 'option_value' => 'My Site']);
        Option::create(['option_name' => 'description', 'option_value' => 'A site']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/settings');
        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'My Site');
    }

    public function test_api_key_works_as_query_param(): void
    {
        Post::create(['post_title' => 'Query Key', 'post_content' => 'C1', 'slug' => 'query-key', 'post_status' => 'publish']);

        $response = $this->getJson('/api/posts?api_key='.$this->apiKey);
        $response->assertStatus(200);
    }

    public function test_api_pagination(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            Post::create(['post_title' => "Post $i", 'post_content' => 'C', 'slug' => "post-$i", 'post_status' => 'publish']);
        }

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/posts?per_page=10');
        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.last_page', 3)
            ->assertJsonCount(10, 'data');
    }

    public function test_api_search_posts(): void
    {
        Post::create(['post_title' => 'Searchable Post', 'post_content' => 'C1', 'slug' => 'searchable', 'post_status' => 'publish']);
        Post::create(['post_title' => 'Other Post', 'post_content' => 'C2', 'slug' => 'other', 'post_status' => 'publish']);

        $response = $this->withHeader('X-API-Key', $this->apiKey)->getJson('/api/posts?search=Searchable');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}

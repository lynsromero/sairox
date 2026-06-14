<?php

namespace Tests\Unit;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_with_post_type_page(): void
    {
        $page = Page::create([
            'post_title' => 'Test Page',
            'post_content' => 'Page content.',
            'slug' => 'test-page',
        ]);

        $this->assertEquals('page', $page->post_type);
    }

    public function test_global_scope_only_returns_pages(): void
    {
        Page::create(['post_title' => 'Page 1', 'post_content' => 'C1', 'slug' => 'p1']);
        Page::create(['post_title' => 'Page 2', 'post_content' => 'C2', 'slug' => 'p2']);
        Post::create(['post_title' => 'Post 1', 'post_content' => 'C3', 'slug' => 'post-1']);

        $this->assertCount(2, Page::all());
        $this->assertCount(3, Post::all());
    }

    public function test_has_available_templates(): void
    {
        $templates = Page::getAvailableTemplates();

        $this->assertIsArray($templates);
        $this->assertArrayHasKey('default', $templates);
        $this->assertArrayHasKey('full_width', $templates);
        $this->assertArrayHasKey('sidebar', $templates);
        $this->assertArrayHasKey('blank', $templates);
        $this->assertArrayHasKey('landing', $templates);
    }
}

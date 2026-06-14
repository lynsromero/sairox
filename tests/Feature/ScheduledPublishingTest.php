<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduledPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_future_scheduled_at_changes_status_to_scheduled(): void
    {
        $post = Post::create([
            'post_title' => 'Future Post',
            'post_content' => 'Content',
            'slug' => 'future-post',
            'post_status' => 'draft',
            'scheduled_at' => now()->addHours(2),
        ]);

        $this->assertEquals('scheduled', $post->post_status);
        $this->assertNotNull($post->scheduled_at);
    }

    public function test_setting_past_scheduled_at_does_not_change_status(): void
    {
        $post = Post::create([
            'post_title' => 'Past Post',
            'post_content' => 'Content',
            'slug' => 'past-post',
            'post_status' => 'draft',
            'scheduled_at' => now()->subHour(),
        ]);

        $this->assertEquals('draft', $post->post_status);
    }

    public function test_scheduler_publishes_due_posts(): void
    {
        Post::create([
            'post_title' => 'Due Post',
            'post_content' => 'Content',
            'slug' => 'due-post',
            'post_status' => 'scheduled',
            'scheduled_at' => now()->subMinute(),
        ]);

        Post::where('post_status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->update(['post_status' => 'publish']);

        $this->assertEquals(1, Post::where('post_status', 'publish')->where('slug', 'due-post')->count());
    }

    public function test_scheduled_posts_do_not_appear_on_front_page(): void
    {
        Post::create([
            'post_title' => 'Visible Post',
            'post_content' => 'Content',
            'slug' => 'visible',
            'post_status' => 'publish',
        ]);

        Post::create([
            'post_title' => 'Hidden Scheduled',
            'post_content' => 'Content',
            'slug' => 'hidden',
            'post_status' => 'scheduled',
            'scheduled_at' => now()->addDay(),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Visible Post');
        $response->assertDontSee('Hidden Scheduled');
    }

    public function test_removing_scheduled_at_reverts_status_to_draft(): void
    {
        $post = Post::create([
            'post_title' => 'Revert Post',
            'post_content' => 'Content',
            'slug' => 'revert',
            'post_status' => 'scheduled',
            'scheduled_at' => now()->addDay(),
        ]);

        $post->update(['scheduled_at' => null]);

        $this->assertEquals('draft', $post->fresh()->post_status);
        $this->assertNull($post->fresh()->scheduled_at);
    }
}

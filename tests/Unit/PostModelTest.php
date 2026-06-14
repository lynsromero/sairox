<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Test Post',
            'post_content' => 'Test content here.',
            'slug' => 'test-post',
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);

        $this->assertDatabaseHas('posts', ['slug' => 'test-post', 'post_type' => 'post']);
        $this->assertEquals('Test Post', $post->post_title);
    }

    public function test_belongs_to_author(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Author Post',
            'post_content' => 'Content',
            'slug' => 'author-post',
        ]);

        $this->assertInstanceOf(User::class, $post->author);
        $this->assertEquals($user->id, $post->author->id);
    }

    public function test_has_categories_relation(): void
    {
        $post = Post::create([
            'post_title' => 'Categorized Post',
            'post_content' => 'Content',
            'slug' => 'categorized-post',
        ]);

        $this->assertInstanceOf(Collection::class, $post->categories);
    }

    public function test_has_tags_relation(): void
    {
        $post = Post::create([
            'post_title' => 'Tagged Post',
            'post_content' => 'Content',
            'slug' => 'tagged-post',
        ]);

        $this->assertInstanceOf(Collection::class, $post->tags);
    }

    public function test_soft_deletes(): void
    {
        $post = Post::create([
            'post_title' => 'Deletable Post',
            'post_content' => 'Content',
            'slug' => 'deletable-post',
        ]);

        $post->delete();

        $this->assertSoftDeleted($post);
    }
}

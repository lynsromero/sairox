<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentRevisionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_revision_on_post_creation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Original Title',
            'post_content' => 'Original content',
            'slug' => 'revision-test',
            'post_status' => 'publish',
        ]);

        $this->assertEquals(1, $post->revisions()->count());

        $revision = $post->revisions()->first();
        $this->assertEquals($user->id, $revision->user_id);
        $this->assertEquals('Original Title', $revision->data['post_title']);
    }

    public function test_creates_revision_on_update(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Title',
            'post_content' => 'Content',
            'slug' => 'update-test',
            'post_status' => 'publish',
        ]);

        $post->update(['post_title' => 'Updated Title']);

        $this->assertEquals(2, $post->revisions()->count());

        $updateRevision = $post->revisions()->orderBy('id', 'desc')->first();
        $this->assertEquals('Updated Title', $updateRevision->data['post_title']);
    }

    public function test_does_not_create_revision_without_meaningful_changes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Title',
            'post_content' => 'Content',
            'slug' => 'nochange-test',
            'post_status' => 'publish',
        ]);

        $post->save();

        $this->assertEquals(1, $post->revisions()->count());
    }

    public function test_restores_revision(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Original',
            'post_content' => 'Original content',
            'slug' => 'restore-test',
            'post_status' => 'publish',
        ]);

        $post->update(['post_title' => 'Changed Title']);

        $firstRevision = $post->revisions()->orderBy('id', 'asc')->first();
        $post->restoreRevision($firstRevision);

        $this->assertEquals('Original', $post->fresh()->post_title);
        $this->assertEquals(3, $post->revisions()->count());

        $restoreNote = $post->revisions()->orderBy('id', 'desc')->first();
        $this->assertEquals('Restored revision #'.$firstRevision->id, $restoreNote->note);
    }

    public function test_enforces_maximum_50_revisions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Original',
            'post_content' => 'Content',
            'slug' => 'max-revisions',
            'post_status' => 'publish',
        ]);

        for ($i = 0; $i < 54; $i++) {
            $post->update(['post_title' => 'Title '.$i]);
        }

        $this->assertLessThanOrEqual(50, $post->revisions()->count());
        $this->assertEquals(50, $post->revisions()->count());
    }

    public function test_diff_revision_returns_changes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Original Title',
            'post_content' => 'Original content',
            'post_excerpt' => 'Original excerpt',
            'slug' => 'diff-test',
            'post_status' => 'publish',
        ]);

        $post->update(['post_title' => 'Updated Title']);

        $rev1 = $post->revisions()->orderBy('id', 'asc')->first();
        $rev2 = $post->revisions()->orderBy('id', 'desc')->first();

        $diffs = $post->diffRevision($rev1, $rev2);

        $this->assertArrayHasKey('post_title', $diffs);
        $this->assertEquals('Original Title', $diffs['post_title']['old']);
        $this->assertEquals('Updated Title', $diffs['post_title']['new']);
    }

    public function test_revisions_relation_is_morph_many(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Title',
            'post_content' => 'Content',
            'slug' => 'relation-test',
            'post_status' => 'publish',
        ]);

        $this->assertInstanceOf(MorphMany::class, $post->revisions());
    }

    public function test_revision_stores_user_and_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'Storing Test',
            'post_content' => 'Some content here',
            'slug' => 'storing-test',
            'post_status' => 'publish',
        ]);

        $revision = $post->revisions()->first();

        $this->assertEquals($user->id, $revision->user_id);
        $this->assertIsArray($revision->data);
        $this->assertEquals('Storing Test', $revision->data['post_title']);
        $this->assertEquals('Some content here', $revision->data['post_content']);
    }

    public function test_revision_user_relation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::create([
            'post_author' => $user->id,
            'post_title' => 'User Rel',
            'post_content' => 'Content',
            'slug' => 'user-rel',
            'post_status' => 'publish',
        ]);

        $revision = $post->revisions()->first();
        $this->assertInstanceOf(User::class, $revision->user);
        $this->assertEquals($user->id, $revision->user->id);
    }
}

<?php

namespace Tests\Unit;

use App\Models\MediaFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaFileModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_media_file(): void
    {
        $media = MediaFile::create([
            'title' => 'Test Image',
            'file_path' => 'uploads/test.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $this->assertDatabaseHas('media_files', ['title' => 'Test Image']);
        $this->assertEquals('image/jpeg', $media->file_type);
    }

    public function test_is_image_detection(): void
    {
        $image = MediaFile::create(['file_path' => 'test.jpg', 'file_type' => 'image/jpeg']);
        $svg = MediaFile::create(['file_path' => 'test.svg', 'file_type' => 'image/svg+xml']);
        $pdf = MediaFile::create(['file_path' => 'test.pdf', 'file_type' => 'application/pdf']);

        $this->assertTrue($image->isImage());
        $this->assertTrue($svg->isSvg());
        $this->assertFalse($pdf->isImage());
    }

    public function test_human_readable_size(): void
    {
        $kb = MediaFile::create(['file_path' => 'kb.jpg', 'file_type' => 'image/jpeg', 'file_size' => 2048]);
        $mb = MediaFile::create(['file_path' => 'mb.jpg', 'file_type' => 'image/jpeg', 'file_size' => 1048576]);
        $empty = MediaFile::create(['file_path' => 'empty.jpg', 'file_type' => 'image/jpeg', 'file_size' => 0]);

        $this->assertStringContainsString('KB', $kb->humanReadableSize());
        $this->assertStringContainsString('MB', $mb->humanReadableSize());
        $this->assertEquals('0 B', $empty->humanReadableSize());
    }

    public function test_url_accessor_returns_url_with_path(): void
    {
        $media = MediaFile::create(['title' => 'With File', 'file_path' => 'uploads/test.jpg', 'file_type' => 'image/jpeg']);
        $this->assertNotNull($media->url);
        $this->assertStringContainsString('test.jpg', $media->url);
    }
}

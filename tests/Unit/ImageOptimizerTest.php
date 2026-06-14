<?php

namespace Tests\Unit;

use App\Models\MediaFile;
use App\Sairox\Media\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageOptimizerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_without_license(): void
    {
        $optimizer = app(ImageOptimizer::class);

        $this->assertTrue($optimizer->canUpload());
    }

    public function test_monthly_count_starts_at_zero(): void
    {
        $optimizer = app(ImageOptimizer::class);

        $this->assertEquals(0, $optimizer->getMonthlyUploadCount());
    }

    public function test_monthly_count_increases_with_uploads(): void
    {
        MediaFile::create([
            'title' => 'Test',
            'file_path' => 'test.jpg',
            'file_type' => 'image/jpeg',
            'created_at' => now(),
        ]);

        $optimizer = app(ImageOptimizer::class);
        $this->assertEquals(1, $optimizer->getMonthlyUploadCount());
    }

    public function test_delete_optimized_versions_does_not_crash(): void
    {
        $media = MediaFile::create([
            'title' => 'Test',
            'file_path' => 'uploads/test.jpg',
            'file_type' => 'image/jpeg',
        ]);

        $optimizer = app(ImageOptimizer::class);
        $optimizer->deleteOptimizedVersions($media);

        $this->assertTrue(true);
    }
}

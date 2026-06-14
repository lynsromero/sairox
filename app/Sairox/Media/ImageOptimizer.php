<?php

namespace App\Sairox\Media;

use App\Models\MediaFile;
use App\Sairox\License\LicenseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    protected int $maxWidth = 1920;

    protected int $maxHeight = 1920;

    protected int $jpegQuality = 85;

    protected LicenseService $license;

    public function __construct(LicenseService $license)
    {
        $this->license = $license;
    }

    public function optimize(UploadedFile $file): UploadedFile
    {
        if (! $this->shouldOptimize()) {
            return $file;
        }

        $mime = $file->getMimeType();

        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
            return $file;
        }

        try {
            $imageInfo = @getimagesize($file->getRealPath());
            if (! $imageInfo) {
                return $file;
            }

            [$width, $height] = $imageInfo;

            if ($width <= $this->maxWidth && $height <= $this->maxHeight) {
                $this->compress($file, $mime);
            } else {
                $this->resizeAndCompress($file, $width, $height, $mime);
            }
        } catch (\Exception $e) {
            Log::warning('Image optimization failed: '.$e->getMessage());
        }

        return $file;
    }

    public function getMonthlyUploadCount(): int
    {
        return MediaFile::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function canUpload(): bool
    {
        if ($this->license->hasFeature('media-upload-unlimited')) {
            return true;
        }

        $maxUploads = $this->license->hasFeature('media-upload-500') ? 500
            : ($this->license->hasFeature('media-upload-100') ? 100 : 50);

        return $this->getMonthlyUploadCount() < $maxUploads;
    }

    protected function shouldOptimize(): bool
    {
        return $this->license->hasFeature('image-optimization-unlimited')
            || $this->license->hasFeature('image-optimization-100');
    }

    protected function compress(UploadedFile $file, string $mime): void
    {
        $path = $file->getRealPath();
        $image = $this->createImage($path, $mime);

        if (! $image) {
            return;
        }

        $this->saveImage($image, $path, $mime);
        imagedestroy($image);
    }

    protected function resizeAndCompress(UploadedFile $file, int $width, int $height, string $mime): void
    {
        $path = $file->getRealPath();
        $image = $this->createImage($path, $mime);

        if (! $image) {
            return;
        }

        $ratio = min($this->maxWidth / $width, $this->maxHeight / $height, 1);
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $resampled = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resampled, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $this->saveImage($resampled, $path, $mime);
        imagedestroy($resampled);
        imagedestroy($image);
    }

    protected function createImage(string $path, string $mime): \GdImage|false
    {
        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/gif' => @imagecreatefromgif($path),
            default => false,
        };
    }

    protected function saveImage(\GdImage $image, string $path, string $mime): void
    {
        match ($mime) {
            'image/jpeg' => imagejpeg($image, $path, $this->jpegQuality),
            'image/png' => imagepng($image, $path, 6),
            'image/webp' => imagewebp($image, $path, $this->jpegQuality),
            'image/gif' => imagegif($image, $path),
            default => null,
        };
    }

    public function deleteOptimizedVersions(MediaFile $media): void
    {
        $disk = Storage::disk(MediaFile::getStorageDisk());
        $path = $media->file_path;

        if ($path) {
            $info = pathinfo($path);
            $extensions = ['webp', 'avif'];

            foreach ($extensions as $ext) {
                $optimizedPath = $info['dirname'].'/'.$info['filename'].'.'.$ext;
                if ($disk->exists($optimizedPath)) {
                    $disk->delete($optimizedPath);
                }
            }
        }
    }
}

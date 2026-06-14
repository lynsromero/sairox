<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'file_type',
        'file_size',
        'alt_text',
        'caption',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function getUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return Storage::disk(static::getStorageDisk())->url($this->file_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        if ($this->isImage()) {
            return Storage::disk(static::getStorageDisk())->url($this->file_path);
        }

        return null;
    }

    public function isImage(): bool
    {
        return $this->file_type && str_starts_with($this->file_type, 'image/');
    }

    public function isSvg(): bool
    {
        return $this->file_type === 'image/svg+xml';
    }

    public function humanReadableSize(): string
    {
        if (! $this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        $size = (float) $this->file_size;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2).' '.$units[$i];
    }

    public static function getStorageDisk(): string
    {
        return config('sairox.media_disk', 'public');
    }
}

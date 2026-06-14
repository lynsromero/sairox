<?php

use App\Models\MediaFile;
use App\Models\Option;
use Illuminate\Support\Facades\Cache;

if (! function_exists('get_option')) {
    function get_option(string $key, mixed $default = null): mixed
    {
        static $cache = [];
        if (isset($cache[$key])) {
            return $cache[$key];
        }
        $option = Option::where('option_name', $key)->first();
        $cache[$key] = $option ? $option->option_value : $default;

        return $cache[$key];
    }
}

if (! function_exists('set_option')) {
    function set_option(string $key, mixed $value): void
    {
        Option::updateOrCreate(
            ['option_name' => $key],
            ['option_value' => $value]
        );
    }
}

if (! function_exists('get_media_url')) {
    function get_media_url($id): ?string
    {
        $media = Cache::remember("media_url_{$id}", 3600, fn () => MediaFile::find($id));

        return $media?->url;
    }
}

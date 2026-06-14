<?php

use App\Models\MediaFile;
use App\Models\Option;
use Illuminate\Support\Facades\Cache;

if (! function_exists('get_option')) {
    function get_option(string $key, mixed $default = null): mixed
    {
        $cacheKey = "option_{$key}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $option = Option::where('option_name', $key)->first();
        $value = $option ? $option->option_value : $default;
        Cache::put($cacheKey, $value, 86400);

        return $value;
    }
}

if (! function_exists('set_option')) {
    function set_option(string $key, mixed $value): void
    {
        Option::updateOrCreate(
            ['option_name' => $key],
            ['option_value' => $value]
        );
        Cache::forget("option_{$key}");
    }
}

if (! function_exists('get_media_url')) {
    function get_media_url($id): ?string
    {
        $media = Cache::remember("media_url_{$id}", 3600, fn () => MediaFile::find($id));

        return $media?->url;
    }
}

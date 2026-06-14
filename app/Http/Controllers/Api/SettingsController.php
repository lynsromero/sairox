<?php

namespace App\Http\Controllers\Api;

use App\Models\Option;
use Illuminate\Http\JsonResponse;

class SettingsController extends ApiController
{
    public function index(): JsonResponse
    {
        $keys = ['title', 'description', 'site_url', 'language', 'timezone'];

        $settings = Option::whereIn('option_name', $keys)
            ->get()
            ->keyBy('option_name')
            ->map(fn ($opt) => $opt->option_value);

        return response()->json(['data' => $settings]);
    }
}

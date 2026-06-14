<?php

namespace App\Http\Controllers\Api;

use App\Models\MediaFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = MediaFile::query();

        if ($request->filled('type')) {
            $query->where('file_type', 'like', $request->input('type').'%');
        }

        return $this->paginate($query->latest(), $request);
    }

    public function show(string $id): JsonResponse
    {
        $media = MediaFile::find($id);

        return $this->single($media, "api:media:{$id}");
    }
}

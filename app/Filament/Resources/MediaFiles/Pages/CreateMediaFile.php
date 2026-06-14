<?php

namespace App\Filament\Resources\MediaFiles\Pages;

use App\Filament\Resources\MediaFiles\MediaFileResource;
use App\Sairox\Media\ImageOptimizer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CreateMediaFile extends CreateRecord
{
    protected static string $resource = MediaFileResource::class;

    protected function beforeCreate(): void
    {
        $optimizer = app(ImageOptimizer::class);

        if (! $optimizer->canUpload()) {
            Log::warning('Media upload blocked: monthly limit reached');
            $this->halt();

            return;
        }

        $data = $this->form->getRawState();
        $file = data_get($data, 'file_path');

        if ($file instanceof UploadedFile) {
            $optimizer->optimize($file);
        }
    }
}

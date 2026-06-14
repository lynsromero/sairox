<?php

namespace App\Filament\Resources\MediaFiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaFileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Upload')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('File')
                            ->disk(static::getDisk())
                            ->directory('uploads')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->required()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($state && ! $get('title')) {
                                    $set('title', pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME));
                                }
                            }),
                    ]),
                Section::make('Details')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255),
                        TextInput::make('alt_text')
                            ->label('Alt Text')
                            ->maxLength(255)
                            ->helperText('Descriptive text for accessibility and SEO.'),
                        Textarea::make('caption')
                            ->label('Caption')
                            ->maxLength(65535)
                            ->helperText('Brief description displayed below the media.'),
                    ]),
            ]);
    }

    protected static function getDisk(): string
    {
        return config('sairox.media_disk', 'public');
    }
}

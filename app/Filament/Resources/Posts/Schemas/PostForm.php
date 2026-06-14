<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Main Content')
                    ->schema([
                        TextInput::make('post_title')->required(),
                        TextInput::make('slug')->required(),
                        RichEditor::make('post_content')->columnSpanFull(),
                    ])->columns(2),

                Section::make('Media & Settings')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Featured Image')
                            ->image()
                            ->disk('public')
                            ->directory('thumbnails')
                            ->visibility('public')
                            ->preserveFilenames() // Optional: keeps original name
                            ->imageEditor()
                            ->columnSpanFull(),

                        Select::make('post_status')
                            ->options(['publish' => 'Publish', 'draft' => 'Draft']),

                        Select::make('post_author')
                            ->relationship('author', 'name'),
                    ])->columns(2),
            ])
            ->columns(1);
    }
}

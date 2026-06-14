<?php

namespace App\Filament\Resources\MediaFiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Preview')
                    ->circular()
                    ->square()
                    ->defaultImageUrl(fn ($record) => $record->isImage()
                        ? $record->thumbnail_url
                        : 'https://placehold.co/80x80?text='.urlencode(pathinfo($record->file_path ?? '', PATHINFO_EXTENSION)))
                    ->extraAttributes(['class' => 'w-12 h-12']),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('human_readable_size')
                    ->label('Size')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

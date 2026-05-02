<?php

namespace App\Filament\Resources\MediaFiles\Pages;

use App\Filament\Resources\MediaFiles\MediaFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListMediaFiles extends ListRecords
{
    protected static string $resource = MediaFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Preview')
                    ->square(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('file_type')
                    ->badge(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ]);
    }
}

<?php

namespace App\Filament\Resources\Widgets;

use App\Filament\Resources\Widgets\Pages\CreateWidget;
use App\Filament\Resources\Widgets\Pages\EditWidget;
use App\Filament\Resources\Widgets\Pages\ListWidgets;
use App\Models\Widget;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WidgetResource extends Resource
{
    protected static ?string $model = Widget::class;

    protected static ?string $navigationLabel = 'Widgets';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Appearance';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Widget Settings')
                    ->schema([
                        Select::make('widget_area_id')
                            ->label('Widget Area')
                            ->relationship('area', 'name')
                            ->required(),
                        Select::make('type')
                            ->label('Widget Type')
                            ->options([
                                'text' => 'Text',
                                'recent_posts' => 'Recent Posts',
                                'categories' => 'Categories',
                                'search' => 'Search',
                                'custom_html' => 'Custom HTML',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('settings', null)),
                        Textarea::make('settings.content')
                            ->label('Content')
                            ->visible(fn (callable $get) => in_array($get('type'), ['text', 'custom_html']))
                            ->helperText(fn (callable $get) => $get('type') === 'custom_html' ? 'Raw HTML/JS' : 'Plain text or HTML'),
                        TextInput::make('settings.title')
                            ->label('Title')
                            ->visible(fn (callable $get) => $get('type') === 'recent_posts'),
                        TextInput::make('settings.count')
                            ->label('Number of posts')
                            ->numeric()
                            ->default(5)
                            ->visible(fn (callable $get) => $get('type') === 'recent_posts'),
                        TextInput::make('settings.title')
                            ->label('Title')
                            ->visible(fn (callable $get) => $get('type') === 'categories'),
                        TextInput::make('settings.title')
                            ->label('Title')
                            ->visible(fn (callable $get) => $get('type') === 'search'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('area.name')
                    ->label('Widget Area'),
                TextColumn::make('order')
                    ->label('Order'),
            ])
            ->defaultSort('order', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWidgets::route('/'),
            'create' => CreateWidget::route('/create'),
            'edit' => EditWidget::route('/{record}/edit'),
        ];
    }
}

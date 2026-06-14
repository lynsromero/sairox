<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use App\Models\Term;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationLabel = 'Menus';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Appearance';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Menu Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(200),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(200)
                            ->unique(ignoreRecord: true),
                        TextInput::make('location')
                            ->label('Location')
                            ->required()
                            ->maxLength(200)
                            ->helperText('e.g., primary, footer'),
                    ]),
                Section::make('Menu Items')
                    ->schema([
                        static::getMenuItemsRepeater(),
                    ]),
            ]);
    }

    protected static function getMenuItemsRepeater(): Component
    {
        return Repeater::make('items')
            ->relationship('items')
            ->orderColumn('order')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(200),
                    Select::make('parent_id')
                        ->label('Parent Item')
                        ->relationship('parent', 'title')
                        ->nullable(),
                    TextInput::make('url')
                        ->label('URL')
                        ->maxLength(255)
                        ->helperText('Leave empty if linking to a page/post'),
                    Select::make('target')
                        ->label('Target')
                        ->options([
                            '_self' => 'Same tab',
                            '_blank' => 'New tab',
                        ])
                        ->default('_self'),
                    Select::make('linkable_type')
                        ->label('Link to')
                        ->options([
                            '' => 'Custom URL',
                            'page' => 'Page',
                            'post' => 'Post',
                            'category' => 'Category',
                        ])
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('linkable_id', null)),
                    Select::make('linkable_id')
                        ->label('Select Item')
                        ->options(fn (callable $get) => static::getLinkableOptions($get('linkable_type')))
                        ->nullable()
                        ->reactive(),
                ]),
            ])
            ->defaultItems(0)
            ->collapsible();
    }

    protected static function getLinkableOptions(?string $type): array
    {
        return match ($type) {
            'page' => Page::where('post_status', 'publish')->pluck('post_title', 'id')->toArray(),
            'post' => Post::where('post_type', 'post')->where('post_status', 'publish')->pluck('post_title', 'id')->toArray(),
            'category' => Term::whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'category'))->pluck('name', 'id')->toArray(),
            default => [],
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug'),
                TextColumn::make('location')
                    ->label('Location')
                    ->badge(),
                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items'),
            ])
            ->defaultSort('name', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}

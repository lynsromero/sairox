<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationLabel = 'All Pages';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Content';
    }

    public static function getNavigationItems(): array
    {
        return [
            ...parent::getNavigationItems(),
            NavigationItem::make('Add Page')
                ->group('Content')
                ->icon(Heroicon::OutlinedPlusCircle)
                ->url(static::getUrl('create'))
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName().'.create')),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Content')
                    ->schema([
                        TextInput::make('post_title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $state ? $set('slug', str($state)->slug()) : null),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(200)
                            ->unique(ignoreRecord: true),
                        RichEditor::make('post_content')
                            ->label('Content')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Page Settings')
                    ->schema([
                        Select::make('post_status')
                            ->label('Status')
                            ->options([
                                'publish' => 'Published',
                                'draft' => 'Draft',
                            ])
                            ->default('draft')
                            ->required(),
                        Select::make('template')
                            ->label('Template')
                            ->options(Page::getAvailableTemplates())
                            ->default('default'),
                        FileUpload::make('thumbnail')
                            ->label('Featured Image')
                            ->image()
                            ->disk('public')
                            ->directory('page-thumbnails')
                            ->visibility('public'),
                        Select::make('post_author')
                            ->label('Author')
                            ->relationship('author', 'name')
                            ->default(fn () => auth()->id())
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post_title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('post_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'publish' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('template')
                    ->label('Template')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Page::getAvailableTemplates()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}

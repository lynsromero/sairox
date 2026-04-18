<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Posts')->badge(Post::count()),

            'published' => Tab::make('Published')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('post_status', 'publish'))
                ->badge(Post::where('post_status', 'publish')->count())
                ->badgeColor('success'),

            'drafts' => Tab::make('Drafts')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('post_status', 'draft'))
                ->badge(Post::where('post_status', 'draft')->count())
                ->badgeColor('warning'),
            'trash' => Tab::make('Trash')
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed())
                ->badge(Post::onlyTrashed()->count())
                ->badgeColor('danger')
                ->icon('heroicon-m-trash'),
        ];
    }
}


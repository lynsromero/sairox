<?php

namespace App\Providers;

use App\Sairox\ThemeManager;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $theme = app(ThemeManager::class)->getActiveTheme();

            if ($theme && ($theme['stack'] ?? 'blade') === 'blade') {
                $this->loadViewsFrom($theme['path'].'/views', 'theme');
            }
        } catch (\Exception $e) {
            // Tables may not exist yet (migrations not run)
        }

        $this->loadViewsFrom(resource_path('views/front'), 'theme');
        $this->loadViewsFrom(resource_path('views/front'), 'sairox-core');
    }
}

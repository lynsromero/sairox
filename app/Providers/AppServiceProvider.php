<?php

namespace App\Providers;

use App\Models\Option;
use App\Sairox\License\LicenseService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LicenseService::class);
    }

    public function boot(): void
    {
        Blade::if('feature', function (string $feature) {
            return app(LicenseService::class)->hasFeature($feature);
        });

        try {
            Option::where('autoload', 'yes')->get()->each(fn ($opt) => Cache::put("option_{$opt->option_name}", $opt->option_value, 86400));
        } catch (\Exception $e) {
            // Table may not exist yet (migrations not run)
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearSairoxCache extends Command
{
    protected $signature = 'sairox:clear-cache';

    protected $description = 'Clear all Sairox CMS caches including page cache.';

    public function handle(): void
    {
        Cache::flush();

        $this->info('All Sairox caches cleared successfully.');
    }
}

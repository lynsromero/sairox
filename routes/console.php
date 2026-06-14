<?php

use App\Models\Post;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Post::where('post_status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->update(['post_status' => 'publish']);
})->everyMinute()->name('publish-scheduled-posts');

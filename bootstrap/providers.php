<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\ThemeServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    ThemeServiceProvider::class,
    PermissionServiceProvider::class,
];

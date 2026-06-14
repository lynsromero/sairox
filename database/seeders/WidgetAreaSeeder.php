<?php

namespace Database\Seeders;

use App\Models\WidgetArea;
use Illuminate\Database\Seeder;

class WidgetAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Sidebar', 'slug' => 'sidebar'],
            ['name' => 'Footer', 'slug' => 'footer'],
        ];

        foreach ($areas as $area) {
            WidgetArea::create($area);
        }
    }
}

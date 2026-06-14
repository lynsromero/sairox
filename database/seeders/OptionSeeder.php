<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            ['option_name' => 'site_title', 'option_value' => 'Sairox CMS', 'autoload' => 'yes'],
            ['option_name' => 'tagline', 'option_value' => '', 'autoload' => 'yes'],
            ['option_name' => 'posts_per_page', 'option_value' => 10, 'autoload' => 'yes'],
            ['option_name' => 'site_language', 'option_value' => 'en', 'autoload' => 'yes'],
            ['option_name' => 'permalink_structure', 'option_value' => '/{slug}', 'autoload' => 'yes'],
            ['option_name' => 'license_key', 'option_value' => '', 'autoload' => 'yes'],
            ['option_name' => 'license_data', 'option_value' => '{}', 'autoload' => 'yes'],
            ['option_name' => 'active_theme', 'option_value' => 'sairox-default', 'autoload' => 'yes'],
        ];

        foreach ($options as $option) {
            Option::create($option);
        }
    }
}

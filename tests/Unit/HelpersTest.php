<?php

namespace Tests\Unit;

use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_option_returns_default(): void
    {
        $this->assertNull(get_option('nonexistent'));
        $this->assertEquals('fallback', get_option('nonexistent', 'fallback'));
    }

    public function test_set_and_get_option(): void
    {
        set_option('site_title', 'My Site');

        $this->assertEquals('My Site', get_option('site_title'));
    }

    public function test_set_option_updates_existing(): void
    {
        set_option('site_title', 'First');
        set_option('site_title', 'Second');

        $this->assertEquals('Second', get_option('site_title'));
        $this->assertCount(1, Option::where('option_name', 'site_title')->get());
    }
}

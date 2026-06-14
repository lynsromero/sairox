<?php

namespace Tests\Feature;

use App\Sairox\License\LicenseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_free_plan_without_key(): void
    {
        $service = app(LicenseService::class);
        $result = $service->verify('');

        $this->assertFalse($result['valid']);
        $this->assertEquals('free', $result['plan']);
    }

    public function test_returns_free_plan_for_missing_option(): void
    {
        $service = app(LicenseService::class);
        $result = $service->verify();

        $this->assertFalse($result['valid']);
        $this->assertEquals('free', $result['plan']);
    }

    public function test_feature_check_on_free_plan(): void
    {
        config(['sairox.free_features' => ['posts']]);
        $service = app(LicenseService::class);

        $this->assertFalse($service->hasFeature('posts'));
    }

    public function test_cached_license_used_on_failure(): void
    {
        $service = app(LicenseService::class);

        $this->assertFalse($service->hasFeature('nonexistent'));
    }
}

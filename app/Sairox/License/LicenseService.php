<?php

namespace App\Sairox\License;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LicenseService
{
    protected string $verifyUrl = 'https://sairox.com/api/v1/verify';

    protected int $cacheTtl = 86400;

    public function verify(?string $key = null): array
    {
        $key = $key ?? get_option('license_key', '');

        if (empty($key)) {
            return ['valid' => false, 'features' => [], 'plan' => 'free'];
        }

        $fingerprint = md5(
            request()->getHost().
            base_path().
            config('app.key')
        );

        $checksums = $this->getCriticalFileChecksums();

        try {
            $response = Http::retry(2, 100)->post($this->verifyUrl, [
                'license_key' => $key,
                'domain' => request()->getHost(),
                'fingerprint' => $fingerprint,
                'version' => config('app.version'),
                'checksums' => $checksums,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Cache::put('license_data', $data, $this->cacheTtl);

                return $data;
            }

            if ($response->status() === 403) {
                Cache::forget('license_data');

                return ['valid' => false, 'features' => [], 'plan' => 'invalid'];
            }
        } catch (\Exception $e) {
            $cached = Cache::get('license_data');
            if ($cached) {
                return $cached;
            }
        }

        return ['valid' => false, 'features' => [], 'plan' => 'free'];
    }

    public function hasFeature(string $feature): bool
    {
        $license = $this->verify();

        return $license['valid'] && in_array($feature, $license['features'] ?? []);
    }

    public function getPlan(): string
    {
        return $this->verify()['plan'] ?? 'free';
    }

    protected function getCriticalFileChecksums(): array
    {
        $files = [
            app_path('Sairox/License/LicenseService.php'),
            app_path('Sairox/License/FeatureMiddleware.php'),
        ];

        $checksums = [];
        foreach ($files as $file) {
            if (file_exists($file)) {
                $checksums[basename($file)] = md5_file($file);
            }
        }

        return $checksums;
    }
}

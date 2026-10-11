<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'name' => 'Precision Core Supply',
                'slug' => 'precision-core-supply',
                'url' => 'https://precisioncoresupply.com',
                'key' => env('SITE_KEY_PRECISION', 'site_pcs_local_dev_key_change_me'),
            ],
            [
                'name' => 'PAK Precision Tools',
                'slug' => 'pkpt',
                'url' => 'https://pkpt.vercel.app',
                'key' => env('SITE_KEY_PKPT', 'site_pkpt_local_dev_key_change_me'),
            ],
            [
                'name' => 'Indus Calibrations',
                'slug' => 'indus-calibrations',
                'url' => 'https://induscalibrations.com',
                'key' => env('SITE_KEY_INDUS', 'site_indus_local_dev_key_change_me'),
            ],
        ];

        foreach ($sites as $row) {
            $site = Site::query()->firstOrNew(['slug' => $row['slug']]);
            $site->fill([
                'name' => $row['name'],
                'url' => $row['url'],
                'is_active' => true,
            ]);
            $site->assignKey($row['key']);
            $site->save();
        }
    }
}

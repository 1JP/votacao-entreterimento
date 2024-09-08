<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'name' => 'sandbox-api-pagseguro',
            'body' => 1,
            'group' => 'api'
        ]);

        Setting::create([
            'name' => 'url-sanbox-api-pagseguro',
            'body' => 'https://sandbox.api.assinaturas.pagseguro.com/',
            'group' => 'api'
        ]);

        Setting::create([
            'name' => 'url-prod-api-pagseguro',
            'body' => 'https://api.assinaturas.pagseguro.com/',
            'group' => 'api'
        ]);

        Setting::create([
            'name' => 'token-api-pagseguro',
            'body' => '2105e1f5-57f1-4f81-8724-37084b6a77448ceb8f6a4f048cce9fb9f9552374524ced7e-c40e-43c7-ae1f-5f02f26024a1',
            'group' => 'api'
        ]);
    }
}

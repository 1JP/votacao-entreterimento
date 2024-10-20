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
            'name' => 'sandbox-payment',
            'body' => 1,
            'group' => 'api-payment'
        ]);

        Setting::create([
            'name' => 'url-sanbox-payment',
            'body' => 'https://sandbox.api.assinaturas.pagseguro.com/',
            'group' => 'api-payment'
        ]);

        Setting::create([
            'name' => 'url-prod-payment',
            'body' => 'https://api.assinaturas.pagseguro.com/',
            'group' => 'api-payment'
        ]);

        Setting::create([
            'name' => 'token-payment',
            'body' => '2105e1f5-57f1-4f81-8724-37084b6a77448ceb8f6a4f048cce9fb9f9552374524ced7e-c40e-43c7-ae1f-5f02f26024a1',
            'group' => 'api-payment'
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\Setting;

class PagSeguroApi
{

    private String $url;
    private String $token;

    public function __construct() {
        $sandbox = Setting::where('name', 'sandbox-api')->first();
        $urlSandbox = Setting::where('name', 'url-sanbox-api')->first();
        $urlProd = Setting::where('name', 'url-prod-api')->first();

        $this->url = $sandbox->body == "1" ? $urlSandbox->body : $urlProd->body;
    }

    public function apiClient()
    {
        dd($this->url);
    }
}
<?php

namespace App\Services;

use App\Models\Setting;

class PaymentApi
{

    private String $url;
    private String $token;

    public function __construct() {
        $sandbox = Setting::where('name', 'sandbox-payment')->first();
        $urlSandbox = Setting::where('name', 'url-sanbox-payment')->first();
        $urlProd = Setting::where('name', 'url-prod-payment')->first();
        $tokenPayment = Setting::where('name', 'token-payment')->first();

        $this->url = $sandbox->body == "1" ? $urlSandbox->body : $urlProd->body;
        $this->token = $tokenPayment->body;
    }

    public function createPlan(array $data)
    {
        $endpoint = "plans";
        $headers = [];
        $body = json_encode($data);

        $response = PaymentApi::exec('POST', $endpoint, $body, $headers);
        return json_decode($response);
    }

    public function getPlan(string $customer_id)
    {
        $endpoint = "plans/".$customer_id;
        $headers = [];
        $body = null;

        $response = PaymentApi::exec('GET', $endpoint, $body, $headers);
        return json_decode($response);
    }
    
    public function updatePlan(array $data, string $customer_id)
    {
        $endpoint = "plans/".$customer_id;
        $headers = [];
        $body = json_encode($data);

        $response = PaymentApi::exec('PUT', $endpoint, $body, $headers);
        return json_decode($response);
    }

    public function activePlan(string $customer_id)
    {
        $endpoint = "plans/".$customer_id."/activate"
        ;
        $headers = [];
        $body = null;

        $response = PaymentApi::exec('PUT', $endpoint, $body, $headers);
        return json_decode($response);
    }

    public function inactivatePlan(string $customer_id)
    {
        $endpoint = "plans/".$customer_id."/inactivate";
        $headers = [];
        $body = null;

        $response = PaymentApi::exec('PUT', $endpoint, $body, $headers);
        return json_decode($response);
    }

    public function exec($method, $endpoint, $body = null, $headers = [])
    {
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$this->token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.$endpoint);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            $error_message = curl_error($ch);
            // Handle the error
            echo "Error: " . $error_message;
        }

        // Close the CURL session
        curl_close($ch);
        
        return $response;
    }
}
<?php

namespace App\Services;

use GuzzleHttp\Client;

class WhatsAppService
{
    protected $client;
    protected $apiUrl;
    protected $apiToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('WHATSAPP_API_URL');
        $this->apiToken = env('WHATSAPP_API_TOKEN');
    }

    public function sendMessage($to, $message)
    {
        $response = $this->client->post($this->apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => ['body' => $message],
            ],
        ]);
        dd($response);
        return json_decode($response->getBody(), true);
    }
}

<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );
        $this->from = config('services.twilio.whatsapp_from');
    }

    public function sendMessage(string $to, string $message): void
    {
        $this->client->messages->create(
            "whatsapp:{$to}",
            [
                'from' => $this->from,
                'body' => $message,
            ]
        );
    }
}
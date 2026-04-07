<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlexSmsService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $senderId;

    public function __construct()
    {
        $this->baseUrl = config('services.flex_sms.base_url');
        $this->clientId = config('services.flex_sms.client_id');
        $this->clientSecret = config('services.flex_sms.client_secret');
        $this->senderId = config('services.flex_sms.sender_id');
    }

    /**
     * Send SMS via Flex SMS Gateway.
     *
     * @param string $recipient
     * @param string $message
     * @return bool
     */
    public function sendSms(string $recipient, string $message): bool
    {
        // Clean phone number: remove '+' and ensure it starts with 255
        $recipient = preg_replace('/[^0-9]/', '', $recipient);
        if (str_starts_with($recipient, '0')) {
            $recipient = '255' . substr($recipient, 1);
        } elseif (!str_starts_with($recipient, '255')) {
            $recipient = '255' . $recipient;
        }

        try {
            $response = Http::withHeaders([
                'X-Client-Id' => $this->clientId,
                'X-Client-Secret' => $this->clientSecret,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/v1/sms/send', [
                'senderId' => $this->senderId,
                'recipient' => $recipient,
                'contents' => $message,
                'schedule' => null,
                'schedule_type' => 'once',
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Flex SMS API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Flex SMS Exception: ' . $e->getMessage());
            return false;
        }
    }
}

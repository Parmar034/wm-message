<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    /**
     * Send a WhatsApp message using Meta Cloud API
     *
     * @param string $to Receiver's phone number (with country code, no +)
     * @param string $message Text message to send
     * @return array
     */
    // public function sendMessage(string $to, string $message): array
    // {
    //     $url = 'https://graph.facebook.com/v19.0/' . env('WHATSAPP_PHONE_NUMBER_ID') . '/messages';


    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('WHATSAPP_ACCESS_TOKEN'),
    //         'Content-Type'  => 'application/json',
    //     ])->post($url, [
    //     'messaging_product' => 'whatsapp',
    //     'to' => $to, // e.g. '917984319868'
    //     'type' => 'template',
    //     'template' => [
    //         'name' => 'hello_world',
    //         'language' => [
    //             'code' => 'en_US',
    //         ],
    //     ],
    // ]);

    //     return $response->json();
    // }


    public function sendMessage(string $to, string $message)
    {

        $url = 'https://graph.facebook.com/v22.0/884086958126864/messages';

        // Send the request to WhatsApp Graph API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WHATSAPP_ACCESS_TOKEN'),
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $to,
            "type" => "template",
            "template" => [
                "name" => "hello_world",  // your APPROVED template name
                "language" => ["code" => "en_US"]
            ]
        ]);

        // Return WhatsApp API response
        return response()->json($response->json(), $response->status());
    }

    public function sendTextMessage(string $to, string $message): array
    {
        $url = 'https://graph.facebook.com/v19.0/' . env('WHATSAPP_PHONE_NUMBER_ID') . '/messages';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WHATSAPP_ACCESS_TOKEN'),
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $to, // Example: 919876543210
            'type'              => 'text',
            'text'              => [
                'preview_url' => false,
                'body' => $message,
            ],
        ]);

        return $response->json();
    }
}

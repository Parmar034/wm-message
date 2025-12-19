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

        $url = 'https://graph.facebook.com/v22.0/2651531405206382/messages';


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WHATSAPP_ACCESS_TOKEN'),
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $to, // Example: 919876543210
            'type' => 'template',
            'template' => [
                'name' => 'dispatch_invoice_details_all',
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => 'ABC Traders'],          // {{1}}
                            ['type' => 'text', 'text' => 'INV-2025-1045'],        // {{2}}
                            ['type' => 'text', 'text' => '08-12-2025'],           // {{3}}
                            ['type' => 'text', 'text' => 'Base Oil SN-500'],      // {{4}}
                            ['type' => 'text', 'text' => '10,000'],              // {{5}}
                            ['type' => 'text', 'text' => '₹7,50,000'],           // {{6}}
                            ['type' => 'text', 'text' => 'Road Transport'],      // {{7}}
                            ['type' => 'text', 'text' => 'GJ-01-AB-1234'],        // {{8}}
                            ['type' => 'text', 'text' => 'LR-889945'],            // {{9}}
                            ['type' => 'text', 'text' => '9876543210'],           // {{10}}
                        ]
                    ]
                ]
            ]
        ]);


        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . env('WHATSAPP_ACCESS_TOKEN'),
        //     'Content-Type'  => 'application/json',
        // ])->post($url, [
        //     'messaging_product' => 'whatsapp',
        //     'recipient_type'    => 'individual',
        //     'to'                => $to, // Example: 919876543210
        //     'type'              => 'text',
        //     'text'              => [
        //         'preview_url' => false,
        //         'body' => "Hello World",
        //     ],
        // ]);

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

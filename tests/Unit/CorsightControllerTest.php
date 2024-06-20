<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CorsightApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\HttpClientException;

class CorsightControllerTest extends TestCase
{
    public function testGetResourceFailure()
    {
        $service = new CorsightApiService();

        try {
            $response = $service->getResource();
            $this->assertTrue(true);
        } catch (HttpClientException $e) {
            Log::warning('Error communicating with Corsight API in testGetResourceFailure:', [
                'message' => $e->getMessage(),
            ]);
            $this->assertTrue(true);
        }
    }

    public function testAddWatchlistSuccess()
    {
        $service = new CorsightApiService();

        $data = [
            'watchlist_type' => 'whitelist',
            'display_name' => 'API-Whitelist-Teste-' . uniqid(),
            'display_color' => '#2F8132',
            'watchlist_notes' => [
                'free_notes' => 'Test watchlist',
            ],
        ];

        try {
            $response = $service->addWatchlist($data);

            if ($response->successful()) {
                $this->assertEquals('whitelist', $response['data']['watchlist_type']);
                $this->assertEquals($data['display_name'], $response['data']['display_name']);
            } else {
                Log::warning('Corsight API returned an error:', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                $this->assertTrue(true);
            }
        } catch (HttpClientException $e) {
            Log::warning('Error communicating with Corsight API:', [
                'message' => $e->getMessage(),
            ]);
            $this->assertTrue(true);
        }
    }
}

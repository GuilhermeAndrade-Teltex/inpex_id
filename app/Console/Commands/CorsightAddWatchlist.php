<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CorsightApiService;
use Illuminate\Support\Facades\Log;
use App\Models\Config;

class CorsightAddWatchlist extends Command
{
    protected $signature = 'corsight:add-watchlist {watchlist_type} {display_name} {display_color} {free_notes}';
    protected $description = 'Add a watchlist to Corsight API';
    protected $corsightApiService;

    public function __construct(CorsightApiService $corsightApiService)
    {
        parent::__construct();
        $this->corsightApiService = $corsightApiService;
    }

    public function handle()
    {
        $watchlistType = $this->argument('watchlist_type');
        $displayName = $this->argument('display_name');
        $displayColor = $this->argument('display_color');
        $freeNotes = $this->argument('free_notes');

        $data = [
            'watchlist_type' => $watchlistType,
            'display_name' => $displayName,
            'display_color' => $displayColor,
            'watchlist_notes' => [
                'free_notes' => $freeNotes,
            ],
        ];

        $response = $this->corsightApiService->addWatchlist($data);

        if ($response && $response->successful()) {
            $responseData = $response->json();
            $watchlistId = $responseData['data']['watchlist_id'] ?? null;

            if ($watchlistId) {
                // Atualize a tabela de configuração
                $config = Config::firstOrNew(['id' => 1]);
                $config->corsight_whitelist_id = $watchlistId;
                $config->save();

                $this->info('Watchlist added successfully. Watchlist ID: ' . $watchlistId);
            } else {
                $this->error('Failed to retrieve watchlist ID from response.');
            }
        } else {
            $this->error('Failed to add watchlist');
            if ($response) {
                $this->error('Response status: ' . $response->status());
                $this->error('Response body: ' . $response->body());
            }
        }
    }
}

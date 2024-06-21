<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CorsightApiService;

class CorsightListWatchlists extends Command
{
    protected $signature = 'corsight:list-watchlists';
    protected $description = 'List all watchlists from Corsight API';

    protected $corsightApiService;

    public function __construct(CorsightApiService $corsightApiService)
    {
        parent::__construct();
        $this->corsightApiService = $corsightApiService;
    }

    public function handle()
    {
        try {
            $watchlists = $this->corsightApiService->listWatchlists();
            $this->info('Watchlists:');
            $this->line(json_encode($watchlists, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->error('Error fetching watchlists: ' . $e->getMessage());
        }
    }
}

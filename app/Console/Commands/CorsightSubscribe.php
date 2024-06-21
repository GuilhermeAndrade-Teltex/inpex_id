<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CorsightApiService;
use Illuminate\Support\Facades\Log;

class CorsightSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corsight:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $corsightApiService;

    public function __construct(CorsightApiService $corsightApiService)
    {
        parent::__construct();
        $this->corsightApiService = $corsightApiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->corsightApiService->getEvents(function ($eventData) {
            Log::info('Evento recebido:', $eventData);
        });
    }
}

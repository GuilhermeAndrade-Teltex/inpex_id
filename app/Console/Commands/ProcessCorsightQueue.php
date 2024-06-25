<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\CorsightQueue;
use App\Models\School;
use App\Models\Student;
use App\Services\CorsightApiService;

class ProcessCorsightQueue extends Command
{
    protected $signature = 'corsight:process-queue';
    protected $description = 'Process the Corsight API queue';

    protected $corsightApiService;

    public function __construct(CorsightApiService $corsightApiService)
    {
        parent::__construct();
        $this->corsightApiService = $corsightApiService;
    }

    public function handle()
    {
        $records = CorsightQueue::whereIn('status', ['NOT_SEND'])->get();

        foreach ($records as $record) {
            try {
                $data = json_decode($record->data, true);

                Log::info('Processing record', ['record' => $record, 'decoded_data' => $data]);

                if ($record->endpoint === 'addWatchlist') {
                    $response = $this->corsightApiService->addWatchlist($data);
                    $body = json_decode($response->body(), TRUE);
                    $school = School::findOrFail($record->module_id);
                    if ($response->successful()) {
                        $record->status = 'SEND';
                        $record->log = json_encode(['message' => 'Watchlist created with successful', 'response' => $body['metadata']['msg']]);

                        $school->watchlist_id = $body['data']['watchlist_id'];
                        $school->save();
                    } else {
                        $record->status = 'ERROR';
                        $record->log = $response->getMessage();
                        Log::error('Error processing record', ['exception' => $response->getMessage()]);
                    }
                } elseif ($record->endpoint === 'addPerson') {
                    $response = $this->corsightApiService->addPerson($data);
                    $student = Student::findOrFail($record->module_id);
                    if ($response['metadata']['success_list']['0']['success'] = true) {
                        $record->status = 'SEND';
                        $record->log = json_encode(['message' => 'Person added successfully', 'response' => $response['metadata']['success_list']['0']['msg']]);

                        $student->faces_id = $response['data']['pois']['0']['poi_id'];
                        $student->save();
                    } else {
                        $record->status = 'ERROR';
                        $record->log = json_encode(['response' => $response->getMessage()]);
                    }
                } elseif ($record->endpoint === 'addFaces') {
                    $response = $this->corsightApiService->addFaces($data);
                    $student = Student::findOrFail($record->module_id);
                    if ($response->successful()) {
                        $record->status = 'SEND';
                        $record->log = json_encode(['message' => 'Faces added successfully', 'response' => $response->body()]);

                        $student->faces_id = $response->body();
                        $student->save();
                    } else {
                        $record->status = 'ERROR';
                        $record->log = json_encode(['response' => $response->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                $record->status = 'ERROR';
                $record->log = $e->getMessage();
                Log::error('Error processing record', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }

            $record->updated_at = now();
            $record->save();

            sleep(5);
        }
    }
}

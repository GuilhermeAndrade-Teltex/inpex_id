<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CorsightApiService;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Models\CorsightReads;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;

class CorsightController extends Controller
{
    protected $corsightApiService;
    protected $validationService;
    protected $breadcrumbService;
    protected $latestAppearanceData = [];

    public function __construct(ValidationService $validationService, BreadcrumbService $breadcrumbService, CorsightApiService $corsightApiService)
    {
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
        $this->corsightApiService = $corsightApiService;
    }

    public function listFaces()
    {
        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Pessoas' => 'corsight.faces',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Pessoas';

        // Limitar a quantidade de faces para melhorar a performance
        $faces = CorsightReads::orderBy('created_at', 'desc')->take(50)->get();

        return view('pages.corsight.face-list', compact('faces', 'breadcrumbs', 'pageTitle'));
    }

    public function listWatchlist()
    {
        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Watchlists' => 'corsight.watchlist',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Watchlists';

        $data_watchlists = $this->corsightApiService->listWatchlists();

        return view('pages.corsight.watchlists-list', compact('data_watchlists', 'breadcrumbs', 'pageTitle'));
    }

    public function addWatchlist(Request $request)
    {
        try {
            $data = $request->validate([
                'watchlist_type' => 'required|string',
                'display_name' => 'required|string',
                'display_color' => 'required|string',
                'watchlist_notes' => 'required|array',
            ]);

            $response = $this->corsightApiService->addWatchlist($data);

            if ($response && $response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Failed to create watchlist'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFacesData()
    {
        // Limitar a quantidade de faces retornadas
        $faces = CorsightReads::orderBy('created_at', 'desc')->take(50)->get();
        return response()->json($faces);
    }

    public function getPowerBIData()
    {
        $events = CorsightReads::orderBy('created_at', 'desc')->get();

        $data = $events->map(function ($event) {
            // Get related school and student
            $school = School::where('watchlist_id', $event->watchlists[0]['watchlist_id'])->first();
            $client = $school ? $school->client : null;
            $student = Student::where('cpf', $event->poi_id)->first();

            return [
                'client' => $client ? $client->name : null,
                'watchlist' => $school ? $school->name : null,
                'camera_id' => $event->camera_id,
                'camera_description' => $event->camera_description,
                'poi_id' => $event->poi_id,
                'poi_display_name' => $student ? $student->name : $event->poi_display_name,
                'class' => $student ? $student->class : null,
                'utc_time_recorded' => $event->updated_at,
                'gender_outcome' => $student ? $student->gender : null,
                'age_group_outcome' => $student ? $this->getAgeGroup($student->date_of_birth) : null,
            ];
        });

        return response()->json($data);
    }

    private function getAgeGroup($dateOfBirth)
    {
        $age = Carbon::parse($dateOfBirth)->age;

        if ($age < 10) {
            return '0-9';
        } elseif ($age < 20) {
            return '10-19';
        } elseif ($age < 30) {
            return '20-29';
        } elseif ($age < 40) {
            return '30-39';
        } elseif ($age < 50) {
            return '40-49';
        } elseif ($age < 60) {
            return '50-59';
        } elseif ($age < 70) {
            return '60-69';
        } else {
            return '70+';
        }
    }
}

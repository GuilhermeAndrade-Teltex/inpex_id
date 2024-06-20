<?php

namespace App\Http\Controllers;

use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Models\School;
use App\Models\Client;
use App\Models\CorsightQueue;
use Illuminate\Support\Str;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;

class SchoolController extends Controller
{
    protected $validationService;
    protected $breadcrumbService;

    public function __construct(ValidationService $validationService, BreadcrumbService $breadcrumbService)
    {
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Escolas' => 'school.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Escolas';

        return view('pages.school.school-list', compact('schools', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Escolas' => 'school.index',
            'Nova Escola' => 'school.create'
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Nova Escola';

        return view('pages.school.school-create', compact('clients', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolRequest $request)
    {
        $validatedData = $request->validated();
        $school = School::create($validatedData);

        // Gerar slug do nome da escola
        $schoolId = str_pad($school->id, 6, '0', STR_PAD_LEFT);
        $schoolName = $schoolId . '_' . $school->name;
        $slug = Str::slug($schoolName, '_');

        // Dados para a chamada da API
        $data = [
            'watchlist_type' => 'whitelist',
            'display_name' => $slug,
            'display_color' => '#00aa00',
            'watchlist_notes' => [
                'free_notes' => "This watchlist was created through InpexID integration at " . now()->format('d/m/Y - H:i:s') . ".",
            ],
        ];

        // Salvar na tabela corsight
        CorsightQueue::create([
            'status' => 'NOT_SEND',
            'module_id' => $school->id,
            'module' => 'corsight_watchlist',
            'data' => json_encode($data),
            'endpoint' => 'addWatchlist',
            'log' => '',
        ]);

        return redirect()->route('school.index')->with('success', 'Escola criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $school = School::findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Escolas' => 'school.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Detalhes da Escola';

        return view('pages.school.school-show', compact('school', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clients = Client::all();
        $school = School::findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Escolas' => 'school.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Editar Escola';

        return view('pages.school.school-edit', compact('school', 'clients', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolRequest $request, School $school)
    {
        $validatedData = $request->validated();
        $school->update($validatedData);

        return redirect()->route('school.index')->with('success', 'Escola atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $school = School::findOrFail($id);
            $school->delete();
            return response()->json(['success', 'Escola removida com sucesso!', 200]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover escola: ' . $e->getMessage()], 500);
        }
    }
}

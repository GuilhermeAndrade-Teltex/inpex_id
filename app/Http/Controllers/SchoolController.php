<?php

namespace App\Http\Controllers;

use App\Services\ValidationService;
use App\Services\AuditService;
use App\Services\BreadcrumbService;
use App\Services\AccessLogService;
use App\Models\School;
use App\Models\Client;
use App\Models\CorsightQueue;
use Illuminate\Support\Str;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;

class SchoolController extends Controller
{
    protected $auditService;
    protected $accessLogService;
    protected $validationService;
    protected $breadcrumbService;

    public function __construct(AuditService $auditService, AccessLogService $accessLogService, ValidationService $validationService, BreadcrumbService $breadcrumbService)
    {
        $this->auditService = $auditService;
        $this->accessLogService = $accessLogService;
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->accessLogService->logAccess("Escolas");

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
        $this->accessLogService->logAccess("Escolas - Inserir");
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

        $data = ' inseriu uma nova escola.';
        $this->auditService->insertLog($school->id, 'school', $data);

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
        $this->accessLogService->logAccess("Escola - Visualizar / id: {$id}");
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
        $this->accessLogService->logAccess("Escola - Editar / id: {$id}");
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
    public function update(UpdateSchoolRequest $request, $id)
    {
        $validatedData = $request->validated();
        $school_old = School::findOrFail($id);
        $school_old = $school_old->attributesToArray();
        
        $school = School::findOrFail($id);

        $school->update($validatedData);
        $this ->auditService->editLog($id, 'school', $school_old, $validatedData);

        return redirect()->route('school.index')->with('success', 'Escola atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $school = School::findOrFail($id);
            $this->auditService->destroyLog($id, 'school', " deletou a escola $school->name.");
            $school->delete();
            return response()->json(['success', 'Escola removida com sucesso!', 200]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover escola: ' . $e->getMessage()], 500);
        }
    }
}

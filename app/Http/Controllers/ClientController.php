<?php

namespace App\Http\Controllers;

use App\Services\AccessLogService;
use App\Services\AuditService;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    protected $auditService;
    protected $accessLogService;
    protected $validationService;
    protected $breadcrumbService;

    public function __construct(AccessLogService $accessLogService, AuditService $auditService, ValidationService $validationService, BreadcrumbService $breadcrumbService)
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
        $this->accessLogService->logAccess("Clientes");
        $clients = Client::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Clientes' => 'client.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Clientes';

        return view('pages.client.client-list', compact('clients', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->accessLogService->logAccess("Cliente - Inserir");

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Clientes' => 'client.index',
            'Novo Cliente' => 'client.create'
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Novo Cliente';

        return view('pages.client.client-create', compact('breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validatedData = $request->validated();

        $module_id = Client::create($validatedData);

        $data = ' inseriu um novo cliente.';
        $this->auditService->insertLog($module_id->id, 'client', $data);

        return redirect()->route('client.index')->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->accessLogService->logAccess("Cliente - Visualizar / id: {$id}");

        $client = Client::findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Clientes' => 'client.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Detalhes do Cliente';

        return view('pages.client.client-show', compact('client', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->accessLogService->logAccess("Cliente - Editar / id: {$id}");

        $client = Client::findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Clientes' => 'client.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Editar Cliente';

        return view('pages.client.client-edit', compact('client', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $validatedData = $request->validated();

        $client_old = Client::findOrFail($id);
        $client_old = $client_old->attributesToArray();
        
        $client = Client::findOrFail($id);
        $client->update($validatedData);

        $this ->auditService->editLog($client->id, 'client', $client_old, $validatedData);

        return redirect()->route('client.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $client = Client::findOrFail($id);
            $this->auditService->destroyLog($id, 'client', " deletou o usuário $client->name.");
            $client->delete();
            return response()->json(['success', 'Cliente removido com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover cliente: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
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

        Client::create($validatedData);

        return redirect()->route('client.index')->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
    public function update(UpdateClientRequest $request, Client $client)
    {
        $validatedData = $request->validated();
        $client->update($validatedData);

        return redirect()->route('client.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(['success', 'Cliente removido com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover cliente: ' . $e->getMessage()], 500);
        }
    }
}

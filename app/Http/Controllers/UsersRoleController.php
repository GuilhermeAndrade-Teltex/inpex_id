<?php

namespace App\Http\Controllers;

use App\Models\UsersRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;

class UsersRoleController extends Controller
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
        $roles = UsersRole::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfis de Usuário' => 'roles.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Perfis de Usuário';

        return view('pages.roles.role-list', compact('roles', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfis de Usuário' => 'roles.index',
            'Novo Perfil' => 'roles.create',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Novo Perfil';

        return view('pages.roles.role-create', compact('breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        UsersRole::create($validatedData);
        return redirect()->route('roles.index')->with('success', 'Perfil de usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersRole $usersRole)
    {
        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfis de Usuário' => 'roles.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Detalhes do Perfil';

        return view('pages.roles.role-show', compact('usersRole', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = UsersRole::findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfis de Usuário' => 'roles.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Editar Perfil';

        return view('pages.roles.role-edit', compact('role', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsersRole $usersRole)
    {
        // Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $usersRole->update($validatedData);
        return redirect()->route('roles.index')->with('success', 'Perfil de usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = UsersRole::findOrFail($id);
            $role->delete();
            return response()->json(['success', 'Perfil de usuário excluído com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover perfil: ' . $e->getMessage()], 500);
        }
    }
}
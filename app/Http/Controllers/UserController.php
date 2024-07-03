<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Services\AccessLogService;
use App\Http\Requests\BasicRequest;
use App\Http\Requests\UserRequest;
use App\Models\UsersRole;
use App\Models\User;
use App\Models\SendToEmail;

class UserController extends Controller
{
    protected $accessLogService;
    protected $validationService;
    protected $breadcrumbService;

    public function __construct(AccessLogService $accessLogService, ValidationService $validationService, BreadcrumbService $breadcrumbService)
    {
        $this->accessLogService = $accessLogService;
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->accessLogService->logAccess("Usuários");
        $users = User::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Usuários' => 'user.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Usuários';

        return view('pages.user.user-list', compact('users', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->accessLogService->logAccess("Usuário - Inserir");
        $roles = UsersRole::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Usuários' => 'user.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Registrar Usuário';

        return view('pages.user.user-create', compact('roles', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'status' => 'ACTIVE',
            'date_created' => now(),
            'date_modified' => now(),
            'created_by' => Auth::user()->id,
            'modified_by' => Auth::user()->id,
            'fullname' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('perfil'),
            'fullname_slug' => $request->input('name'),
            'name' => $request->input('name'),
            'cpf' => $request->input('cpf'),
            'password' => 'primeiroacesso'
        ]);

        $token = Str::random(60);
        $expiresIn = now()->addHours(24)->getTimestamp();

        $user->passwordResets()->create([
            'user_id' => $user->id,
            'date_created' => now(),
            'date_modified' => now(),
            'created_by' => Auth::user()->id,
            'modified_by' => Auth::user()->id,
            'status' => 'ACTIVE',
            'expires_in' => $expiresIn,
            'token' => $token,
        ]);

        SendToEmail::create([
            'module_id' => $user->id,
            'user_id' => $user->id,
            'send_to' => $user->email,
            'page_title' => 'Primeiro Acesso',
            'content_title' => 'Cadastre sua senha',
            'header_description' => 'Seu acesso ao IpexID foi liberado, configure sua senha dentro de 24 horas.',
            'content_description' => "Olá {$user->name},<br><br>
                Seu acesso a IpexID foi liberado. Seu nome de usuário é: <br><br><strong>{$user->email}</strong><br><br>
                Você deve configurar sua senha através desse <a href='" . route('password.reset', $token) . "'>link</a>..",
            'config_file' => 'InpexID',
            'status' => 'NOT_SEND',
            'module' => 'user',
            'date_modified' => now(),
        ]);

        return redirect()->route('user.show', ['id' => $user->id])->with('success', 'Usuário criado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->accessLogService->logAccess("Usuário - Visualizar / id: {$id}");
        $user = User::with('role')->findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Usuários' => 'user.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Visualizar Usuário';

        return view('pages.user.user-view', compact('user', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->accessLogService->logAccess("Usuário - Editar / id: {$id}");
        $user = User::findOrFail($id);
        $roles = UsersRole::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Usuários' => 'user.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Editar Usuário';

        return view('pages.user.user-edit', compact('user', 'roles', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BasicRequest $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->update([
            'date_modified' => now(),
            'modified_by' => Auth::user()->id,
            'fullname' => $request->input('name'),
            'role_id' => $request->input('perfil'),
            'fullname_slug' => $request->input('name'),
            'name' => $request->input('name'),
        ]);

        return redirect()->route('user.show', ['id' => $user->id])->with('success', 'Usuário atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
        // Excluir usuário
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success', 'Usuário excluído com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover usuário: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validate realtime request
     */
    public function validateUserRequest(UserRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        return response()->json(['success' => true], 200);
    }

    public function updateUserRole(string $id)
    {
        $roleDefault = [
            'role_id' => 2
        ];

        User::where('id', $id)->update($roleDefault);
        return response()->json(['success'=> true], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersRoleRequest;
use App\Models\Menus1;
use App\Models\Menus2;
use App\Models\UsersPermission;
use App\Models\UsersRole;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\AccessLogService;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsEmpty;

class UsersRoleController extends Controller
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
        $this->accessLogService->logAccess("Perfis de Usuário");

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
        $this->accessLogService->logAccess("Inserir perfil");

        $menus1 = Menus1::all();
        $menus1 = $menus1->map(function ($menu) {
            return $menu->attributesToArray();
        })->toArray();
        $menus2 = Menus2::all();
        $menus2 = $menus2->map(function ($menu) {
            return $menu->attributesToArray();
        })->toArray();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfis de Usuário' => 'roles.index',
            'Novo Perfil' => 'roles.create',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Novo Perfil';

        return view('pages.roles.role-create', compact('menus1', 'menus2', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userRole = $request->except('menuPermissionsFirst', 'menuPermissionsSecond');
        $permissions = $request->except('name');

        $usersRole = UsersRole::create($userRole);
        $usersRoleId = $usersRole->id;

        foreach ($permissions as $permission_key => $permission_value) {
            foreach ($permission_value as $index => $value) {
                if ($permission_key == 'menuPermissionsFirst') {
                    $data = [
                        'created_by' => Auth::user()->id,
                        'modified_by' => Auth::user()->id,
                        'role_id' => $usersRoleId,
                        'menu1_id' => $index,
                        'show' => $value['view'],
                        'edit' => $value['edit'],
                        'create' => $value['insert'],
                        'destroy' => $value['delete'],
                        'export' => $value['export'],
                        'access_log' => $value['access_log'],
                        'audit_log' => $value['audit_log'],
                    ];
                    UsersPermission::create($data);
                } else {
                    $menu2_id = explode('_', $index);
                    $data = [
                        'created_by' => Auth::user()->id,
                        'modified_by' => Auth::user()->id,
                        'role_id' => $usersRoleId,
                        'menu2_id' => $menu2_id[1],
                        'show' => $value['view'],
                        'edit' => $value['edit'],
                        'create' => $value['insert'],
                        'destroy' => $value['delete'],
                        'export' => $value['export'],
                        'access_log' => $value['access_log'],
                        'audit_log' => $value['audit_log'],
                    ];
                    UsersPermission::create($data);
                }
            }
        }

        $data = ' inseriu um novo perfil.';
        $this->auditService->insertLog($usersRole->id, 'usersRole', $data);

        return redirect()->route('roles.index')->with('success', 'Perfil de usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersRole $usersRole)
    {
        $this->accessLogService->logAccess("Detalhes do Perfil - id: {$usersRole->id}");
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
        $this->accessLogService->logAccess("Editar Perfil / id: {$id}");
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

        $role_old = UsersRole::findOrFail($usersRole->id);
        $role_old = $role_old->attributesToArray();

        $usersRole->update($validatedData);
        $this->auditService->editLog($usersRole->id, 'usersRole', $role_old, $validatedData);

        return redirect()->route('roles.index')->with('success', 'Perfil de usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = UsersRole::findOrFail($id);
            $this->auditService->destroyLog($id, 'usersRole', " deletou o perfil $role->name.");
            $role->delete();
            return response()->json(['success', 'Perfil de usuário excluído com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover perfil: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validate realtime request
     */
    public function validateUsersRoleRequestRequest(StoreUsersRoleRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        return response()->json(['success' => true], 200);
    }
}
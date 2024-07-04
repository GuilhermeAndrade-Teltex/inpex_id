<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersRoleRequest;
use App\Models\Image;
use App\Models\Menus1;
use App\Models\Menus2;
use App\Models\User;
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
                $data = [
                    'created_by' => Auth::user()->id,
                    'modified_by' => Auth::user()->id,
                    'date_created' => now(),
                    'date_modified' => now(),
                    'role_id' => $usersRole->id,
                    'show' => $value['view'] ?? 0,
                    'edit' => $value['edit'] ?? 0,
                    'create' => $value['insert'] ?? 0,
                    'destroy' => $value['delete'] ?? 0,
                    'export' => $value['export'] ?? 0,
                    'access_log' => $value['access_log'] ?? 0,
                    'audit_log' => $value['audit_log'] ?? 0,
                ];

                // Verifica se pelo menos um campo é diferente de 0
                if (array_sum(array_slice($data, 5)) > 0) {
                    if ($permission_key == 'menuPermissionsFirst') {
                        $data['menu1_id'] = $index;

                        if (isset($value['permission_id'])) {
                            UsersPermission::where('id', $value['permission_id'])->update($data);
                        } else {
                            UsersPermission::create($data);
                        }
                    } else {
                        $menu2_id = explode('_', $index)[1];
                        $data['menu2_id'] = $menu2_id;

                        if (isset($value['permission_id'])) {
                            UsersPermission::where('id', $value['permission_id'])->update($data);
                        } else {
                            UsersPermission::create($data);
                        }
                    }
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

        $menus1 = Menus1::all()->toArray();
        $menus2 = Menus2::all()->toArray();

        $permissions = UsersPermission::where("role_id", $id)->get()->toArray();

        $firstMenuPermissions = array();
        foreach ($menus1 as $menu1_value) {
            $firstMenuPermissions[$menu1_value['name']] = [
                'permission_id' => null,
                'menu1_id' => $menu1_value['id'],
                'menu1_name' => $menu1_value['name'],
                'menu1_icon' => $menu1_value['icon'],
                'create' => 0,
                'show' => 0,
                'edit' => 0,
                'destroy' => 0,
                'export' => 0,
                'access_log' => 0,
                'audit_log' => 0,
            ];
            foreach ($permissions as $permission_value) {
                if ($permission_value['menu1_id'] == $menu1_value['id']) {
                    $firstMenuPermissions[$menu1_value['name']] = array_merge($firstMenuPermissions[$menu1_value['name']], [
                        'permission_id' => $permission_value['id'],
                        'create' => $permission_value['create'],
                        'show' => $permission_value['show'],
                        'edit' => $permission_value['edit'],
                        'destroy' => $permission_value['destroy'],
                        'export' => $permission_value['export'],
                        'access_log' => $permission_value['access_log'],
                        'audit_log' => $permission_value['audit_log'],
                    ]);
                }
            }
        }

        $secondMenuPermissions = array();
        foreach ($menus2 as $menu2_value) {
            $secondMenuPermissions[$menu2_value['name']] = [
                'permission_id' => null,
                'menu2_id' => $menu2_value['id'],
                'menu2_name' => $menu2_value['name'],
                'menu2_icon' => $menu2_value['icon'],
                'menus1_id' => $menu2_value['menus1_id'],
                'create' => 0,
                'show' => 0,
                'edit' => 0,
                'destroy' => 0,
                'export' => 0,
                'access_log' => 0,
                'audit_log' => 0,
            ];
            foreach ($permissions as $permission_value) {
                if ($permission_value['menu2_id'] == $menu2_value['id']) {
                    $secondMenuPermissions[$menu2_value['name']] = array_merge($secondMenuPermissions[$menu2_value['name']], [
                        'permission_id' => $permission_value['id'],
                        'create' => $permission_value['create'],
                        'show' => $permission_value['show'],
                        'edit' => $permission_value['edit'],
                        'destroy' => $permission_value['destroy'],
                        'export' => $permission_value['export'],
                        'access_log' => $permission_value['access_log'],
                        'audit_log' => $permission_value['audit_log'],
                    ]);
                }
            }
        }

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfis de Usuário' => 'roles.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Editar Perfil';

        $users = User::where('role_id', $id)->get();
        foreach ($users as $index => $user) {
            $profile_photo = $user->images->where('module', 'users')->pluck('path_original')->toArray();
            $user->status == 'active' ? $users[$index]->status = 'Ativo' : $users[$index]->status = 'Inativo';
            if (empty($profile_photo)) {
                $users[$index]['profile_photo'] = asset('images/logos/profile-default.jpg');
            } else {
                $users[$index]['profile_photo'] = asset('storage/' . $profile_photo[0]);
            }
        }

        return view('pages.roles.role-edit', compact('id', 'firstMenuPermissions', 'secondMenuPermissions', 'users', 'role', 'breadcrumbs', 'pageTitle'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsersRole $usersRole)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $role_old = UsersRole::findOrFail($usersRole->id)->attributesToArray();
            $usersRole->update($validatedData);
            $permissions = $request->except('name');
            $this->auditService->editLog($usersRole->id, 'usersRole', $role_old, $validatedData);

            foreach ($permissions as $permission_key => $permission_value) {
                foreach ($permission_value as $index => $value) {
                    $data = [
                        'created_by' => Auth::user()->id,
                        'modified_by' => Auth::user()->id,
                        'date_created' => now(),
                        'date_modified' => now(),
                        'role_id' => $usersRole->id,
                        'show' => $value['view'] ?? 0,
                        'edit' => $value['edit'] ?? 0,
                        'create' => $value['insert'] ?? 0,
                        'destroy' => $value['delete'] ?? 0,
                        'export' => $value['export'] ?? 0,
                        'access_log' => $value['access_log'] ?? 0,
                        'audit_log' => $value['audit_log'] ?? 0,
                    ];

                    // Verifica se pelo menos um campo é diferente de 0
                    if (array_sum(array_slice($data, 5)) > 0) {
                        if ($permission_key == 'menuPermissionsFirst') {
                            $data['menu1_id'] = $index;

                            if (isset($value['permission_id'])) {
                                UsersPermission::where('id', $value['permission_id'])->update($data);
                            } else {
                                UsersPermission::create($data);
                            }
                        } else {
                            $menu2_id = explode('_', $index)[1];
                            $data['menu2_id'] = $menu2_id;

                            if (isset($value['permission_id'])) {
                                UsersPermission::where('id', $value['permission_id'])->update($data);
                            } else {
                                UsersPermission::create($data);
                            }
                        }
                    }
                }
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
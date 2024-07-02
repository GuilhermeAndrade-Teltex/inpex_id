<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\UsersPermission;
use App\Models\Menus1;
use App\Models\Menus2;
use App\Models\Menus3;

class MenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Auth::check()) {
                $roleId = Auth::user()->role_id;
                $userPermissions = UsersPermission::where('role_id', $roleId)->get();
                $allowedMenus = [];

                if ($userPermissions->isNotEmpty()) {
                    foreach ($userPermissions as $userPermission) {
                        // Verificar permissões para Menu1
                        if (!is_null($userPermission->menu1_id)) {
                            $menu1 = Menus1::with([
                                'menus2' => function ($query) {
                                    $query->orderBy('position')->with([
                                        'menus3' => function ($query) {
                                            $query->orderBy('position');
                                        }
                                    ]);
                                }
                            ])->find($userPermission->menu1_id);

                            if ($menu1) {
                                if (!isset($allowedMenus[$menu1->id])) {
                                    $allowedMenus[$menu1->id] = [
                                        'menu' => $menu1,
                                        'submenus' => [],
                                    ];
                                }

                                foreach ($menu1->menus2 as $menu2) {
                                    if ($menu2 && $userPermission->menu2_id == $menu2->id) {
                                        if (!isset($allowedMenus[$menu1->id]['submenus'][$menu2->id])) {
                                            $allowedMenus[$menu1->id]['submenus'][$menu2->id] = [
                                                'menu' => $menu2,
                                                'submenus' => [],
                                            ];
                                        }

                                        foreach ($menu2->menus3 as $menu3) {
                                            if ($menu3 && $userPermission->menu3_id == $menu3->id) {
                                                $allowedMenus[$menu1->id]['submenus'][$menu2->id]['submenus'][] = $menu3;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Verificar permissões para Menu2 diretamente
                        if (!is_null($userPermission->menu2_id)) {
                            $menu2 = Menus2::with([
                                'menus3' => function ($query) {
                                    $query->orderBy('position');
                                }
                            ])->find($userPermission->menu2_id);

                            if ($menu2) {
                                $menu1 = Menus1::find($menu2->menus1_id);
                                if (!isset($allowedMenus[$menu2->menus1_id])) {
                                    $allowedMenus[$menu2->menus1_id] = [
                                        'menu' => $menu1,
                                        'submenus' => [],
                                    ];
                                }

                                if (!isset($allowedMenus[$menu2->menus1_id]['submenus'][$menu2->id])) {
                                    $allowedMenus[$menu2->menus1_id]['submenus'][$menu2->id] = [
                                        'menu' => $menu2,
                                        'submenus' => [],
                                    ];
                                }

                                foreach ($menu2->menus3 as $menu3) {
                                    if ($menu3 && $userPermission->menu3_id == $menu3->id) {
                                        $allowedMenus[$menu2->menus1_id]['submenus'][$menu2->id]['submenus'][] = $menu3;
                                    }
                                }
                            }
                        }
                    }

                    // Ordenar os menus pelo campo 'position'
                    $allowedMenus = collect($allowedMenus)->sortBy(function ($menu) {
                        return $menu['menu']->position;
                    })->map(function ($menu) {
                        $menu['submenus'] = collect($menu['submenus'])->sortBy(function ($submenu) {
                            return $submenu['menu']->position;
                        })->map(function ($submenu) {
                            $submenu['submenus'] = collect($submenu['submenus'])->sortBy(function ($subsubmenu) {
                                return $subsubmenu->position;
                            })->values()->all();
                            return $submenu;
                        })->values()->all();
                        return $menu;
                    })->values()->all();

                    // Compartilhar menus permitidos com as views
                    view()->share('allowedMenus', $allowedMenus);

                    // Inicializar ações permitidas com valores padrão
                    $allowedActions = [
                        'show' => false,
                        'create' => false,
                        'edit' => false,
                        'destroy' => false,
                        'export' => false,
                        'access_log' => false,
                        'audit_log' => false,
                    ];

                    // Definir ações permitidas com base nas permissões do usuário
                    foreach ($userPermissions as $userPermission) {
                        $allowedActions['show'] = $allowedActions['show'] || $userPermission->show ?? false;
                        $allowedActions['create'] = $allowedActions['create'] || $userPermission->create ?? false;
                        $allowedActions['edit'] = $allowedActions['edit'] || $userPermission->edit ?? false;
                        $allowedActions['destroy'] = $allowedActions['destroy'] || $userPermission->destroy ?? false;
                        $allowedActions['export'] = $allowedActions['export'] || $userPermission->export ?? false;
                        $allowedActions['access_log'] = $allowedActions['access_log'] || $userPermission->access_log ?? false;
                        $allowedActions['audit_log'] = $allowedActions['audit_log'] || $userPermission->audit_log ?? false;
                    }

                    // Compartilhar ações permitidas com as views
                    view()->share('allowedActions', $allowedActions);
                }
            } else {
                // Log::debug('User not authenticated for menu middleware.');
            }
        } catch (\Exception $e) {
            Log::error('An error occurred in menu middleware: ' . $e->getMessage(), ['exception' => $e]);
        }

        return $next($request);
    }
}

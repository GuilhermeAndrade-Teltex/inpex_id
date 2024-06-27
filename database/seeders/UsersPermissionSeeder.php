<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UsersPermission;
use App\Models\Menus1;
use App\Models\Menus2;
use App\Models\Menus3;

class UsersPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Permissões para SuperAdmin (role_id 1)
        $this->seedPermissionsForRole(1);

        // Permissões para Monitoramento (role_id 2)
        $this->seedPermissionsForMonitoramento();
    }

    private function seedPermissionsForRole($role_id)
    {
        $menus1 = Menus1::all();
        $menus2 = Menus2::all();
        $menus3 = Menus3::all();

        foreach ($menus1 as $menu1) {
            UsersPermission::create([
                'role_id' => $role_id,
                'menu1_id' => $menu1->id,
                'create' => 1,
                'show' => 1,
                'edit' => 1,
                'destroy' => 1,
                'export' => 1,
                'access_log' => 1,
                'audit_log' => 1,
                'date_created' => now(),
                'date_modified' => now(),
                'created_by' => 1,
                'modified_by' => 1
            ]);

            foreach ($menus2 as $menu2) {
                if ($menu2->menus1_id == $menu1->id) {
                    UsersPermission::create([
                        'role_id' => $role_id,
                        'menu2_id' => $menu2->id,
                        'create' => 1,
                        'show' => 1,
                        'edit' => 1,
                        'destroy' => 1,
                        'export' => 1,
                        'access_log' => 1,
                        'audit_log' => 1,
                        'date_created' => now(),
                        'date_modified' => now(),
                        'created_by' => 1,
                        'modified_by' => 1
                    ]);

                    foreach ($menus3 as $menu3) {
                        if ($menu3->menus2_id == $menu2->id) {
                            UsersPermission::create([
                                'role_id' => $role_id,
                                'menu3_id' => $menu3->id,
                                'create' => 1,
                                'show' => 1,
                                'edit' => 1,
                                'destroy' => 1,
                                'export' => 1,
                                'access_log' => 1,
                                'audit_log' => 1,
                                'date_created' => now(),
                                'date_modified' => now(),
                                'created_by' => 1,
                                'modified_by' => 1
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function seedPermissionsForMonitoramento()
    {
        $role_id = 2;

        // Recupera o Menu do Corsight
        $corsightMenu1 = Menus1::where('name', 'Corsight')->first();
        if ($corsightMenu1) {
            // Recupera o Submenu corsight/pessoas
            $corsightMenu2 = Menus2::where('name', 'Corsight Pessoas')->where('menus1_id', $corsightMenu1->id)->first();
            if ($corsightMenu2) {
                UsersPermission::create([
                    'role_id' => $role_id,
                    'menu1_id' => $corsightMenu1->id,
                    'menu2_id' => $corsightMenu2->id,
                    'show' => 1,
                    'create' => 0,
                    'edit' => 0,
                    'destroy' => 0,
                    'export' => 0,
                    'access_log' => 0,
                    'audit_log' => 0,
                    'date_created' => now(),
                    'date_modified' => now(),
                    'created_by' => 1,
                    'modified_by' => 1
                ]);
            }
        }
    }
}

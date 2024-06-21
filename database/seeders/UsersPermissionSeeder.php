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
        // Role ID fixo
        $role_id = 1;

        // Recupera todos os Menus1
        $menus1 = Menus1::all();

        // Recupera todos os Menus2
        $menus2 = Menus2::all();

        // Recupera todos os Menus3
        $menus3 = Menus3::all();

        foreach ($menus1 as $menu1) {
            // Permissões para Menus1
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

            // Permissões para Menus2 associados ao Menus1
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

                    // Permissões para Menus3 associados ao Menus2
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
}

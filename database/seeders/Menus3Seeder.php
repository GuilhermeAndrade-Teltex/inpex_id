<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus3;

class Menus3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $submenus = [
            // Submenus para Menus2
            [
                'menus2_id' => 3, // ID do Menu2 correspondente
                'name' => 'Listar Menu 1',
                'url' => '/menu1',
                'icon' => 'fas fa-list',
                'position' => 1,
                'dashboard' => 0,
                'method' => 1,
            ],
            [
                'menus2_id' => 3, // ID do Menu2 correspondente
                'name' => 'Listar Menu 2',
                'url' => '/menu2',
                'icon' => 'fas fa-list',
                'position' => 2,
                'dashboard' => 0,
                'method' => 1,
            ],
            [
                'menus2_id' => 3, // ID do Menu2 correspondente
                'name' => 'Listar Menu 3',
                'url' => '/menu3',
                'icon' => 'fas fa-list',
                'position' => 3,
                'dashboard' => 0,
                'method' => 1,
            ],
        ];

        foreach ($submenus as $submenu) {
            // Verificar se o submenu já existe
            $existingSubmenu = Menus3::where('menus2_id', $submenu['menus2_id'])
                ->where('name', $submenu['name'])
                ->first();

            if (!$existingSubmenu) {
                Menus3::create($submenu);
            }
        }
    }
}

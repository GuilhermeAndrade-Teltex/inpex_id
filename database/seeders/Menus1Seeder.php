<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menus1;

class Menus1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'url' => '/',
                'icon' => 'fas fa-tachometer-alt',
                'position' => 1,
            ],
            [
                'name' => 'Usuários',
                'url' => '/usuarios',
                'icon' => 'fas fa-users',
                'position' => 2,
            ],
            [
                'name' => 'Clientes',
                'url' => '/clientes',
                'icon' => 'fas fa-user-tie',
                'position' => 3,
            ],
            [
                'name' => 'Escolas',
                'url' => '/escolas',
                'icon' => 'fas fa-school',
                'position' => 4,
            ],
            [
                'name' => 'Alunos',
                'url' => '/alunos',
                'icon' => 'fas fa-user-graduate',
                'position' => 5,
            ],
            [
                'name' => 'Corsight',
                'url' => '',
                'icon' => 'fas fa-eye',
                'position' => 6,
            ],
            [
                'name' => 'Configurações',
                'url' => '',
                'icon' => 'fas fa-gears',
                'position' => 7,
            ],
        ];

        foreach ($menus as $menu) {
            Menus1::create($menu);
        }
    }
}

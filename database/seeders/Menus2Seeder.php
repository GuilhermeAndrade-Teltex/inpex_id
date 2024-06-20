<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus2;

class Menus2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $submenus = [
            // Submenus for Corsight
            [
                'menus1_id' => 6,
                'name' => 'Corsight Watchlists',
                'url' => '/corsight/watchlists',
                'icon' => 'fas fa-list',
                'position' => 1,
            ],
            [
                'menus1_id' => 6,
                'name' => 'Corsight Pessoas',
                'url' => '/corsight/pessoas',
                'icon' => 'fas fa-user',
                'position' => 2,
            ],
            
            // Submenus for Configurações
            [
                'menus1_id' => 7,
                'name' => 'Menus',
                'url' => '/config/menus',
                'icon' => 'fas fa-bars',
                'position' => 1,
            ],
            [
                'menus1_id' => 7,
                'name' => 'Perfis',
                'url' => '/perfis',
                'icon' => 'fas fa-user-cog',
                'position' => 2,
            ],
        ];

        foreach ($submenus as $submenu) {
            Menus2::create($submenu);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            UsersRoleSeeder::class,
            Menus1Seeder::class,
            Menus2Seeder::class,
            Menus3Seeder::class,
            UsersPermissionSeeder::class,
        ]);
    }
}

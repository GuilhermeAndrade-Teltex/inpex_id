<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Administrador Sistema',
            'email' => 'administrador@teltex.com.br',
            'password' => Hash::make('teltex@321'),
            'role_id' => 1,
            'status' => 'ACTIVE',
            'date_created' => now(),
            'date_modified' => now(),
            'created_by' => 1,
            'modified_by' => 1,
            'fullname' => 'Administrador Sistema', 
            'fullname_slug' => Str::slug('Administrador Sistema'), 
            'cpf' => '00000000000',
            'remember_token' => Str::random(10),
        ];
    }
}

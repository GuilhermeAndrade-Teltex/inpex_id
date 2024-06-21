<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UsersRole;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersRole>
 */
class UsersRoleFactory extends Factory
{
    protected $model = UsersRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_created' => now(),
            'date_modified' => now(),
            'created_by' => 1,
            'modified_by' => 1,
            'name' => 'Admin',
        ];
    }
}

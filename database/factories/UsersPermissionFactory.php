<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UsersPermission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersPermission>
 */
class UsersPermissionFactory extends Factory
{
    protected $model = UsersPermission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => 1,
            'menu1_id' => $this->faker->numberBetween(1, 8),
            'show' => 1,
            'create' => 1,
            'edit' => 1,
            'destroy' => 1,
            'export' => 1,
            'access_log' => 1,
            'audit_log' => 1,
            'date_created' => now(),
            'date_modified' => now(),
            'created_by' => 1,
            'modified_by' => 1,
        ];
    }
}

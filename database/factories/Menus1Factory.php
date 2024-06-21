<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menus1>
 */
class Menus1Factory extends Factory
{
    protected $model = Menus1::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'url' => '/',
            'icon' => 'fas fa-tachometer-alt',
            'position' => 1,
            'date_created' => now(),
            'date_modified' => now(),
            'created_by' => 1,
            'modified_by' => 1,
        ];
    }
}

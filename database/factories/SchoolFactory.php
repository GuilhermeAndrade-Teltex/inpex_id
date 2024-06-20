<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'client_id' => \App\Models\Client::factory(),
            'name' => $this->faker->company . ' School',
            'education_level' => $this->faker->randomElement(['Elementary', 'Middle', 'High']),
            'responsible' => $this->faker->name,
            'cnpj' => $this->faker->unique()->numerify('##############'),
            'cep' => $this->faker->numerify('########'),
            'address' => $this->faker->streetAddress,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->optional()->secondaryAddress,
            'district' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'observations' => $this->faker->optional()->text,
        ];
    }
}

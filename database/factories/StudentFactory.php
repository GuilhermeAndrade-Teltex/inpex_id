<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'school_id' => \App\Models\School::factory(),
            'full_name' => $this->faker->name,
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'date_of_birth' => $this->faker->date,
            'enrollment' => $this->faker->unique()->numerify('##########'),
            'grade' => $this->faker->randomElement(['1st Grade', '2nd Grade', '3rd Grade', '4th Grade', '5th Grade']),
            'class' => $this->faker->randomElement(['A', 'B', 'C']),
            'education_level' => $this->faker->randomElement(['Elementary', 'Middle', 'High']),
            'responsible_name' => $this->faker->name,
            'responsible_phone' => $this->faker->phoneNumber,
            'responsible_email' => $this->faker->email,
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

<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $positions = Position::pluck('id')->toArray();

        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email(),
            'address' => $this->faker->address,
            'code' => $this->faker->numerify('##########'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'position_id' => $this->faker->randomElement($positions),
            'join_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active', 'resigned', 'terminated']),
            'base_salary' => $this->faker->numberBetween(3000000, 15000000),
        ];
    }
}

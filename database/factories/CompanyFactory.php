<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('???-####')),
            'name' => fake()->company(),
            'email' => fake()->companyEmail(),
            'access_type' => 'internal',
            'is_active' => true,
        ];
    }

    public function external(): static
    {
        return $this->state(fn (array $attrs) => [
            'access_type' => 'external',
        ]);
    }
}

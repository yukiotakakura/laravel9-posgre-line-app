<?php

namespace Database\Factories;

use App\Models\LinedevelopersProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LinedevelopersProvider>
 */
class LinedevelopersProviderFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = LinedevelopersProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => 1657765308,
            'name' => $this->faker->name(),
        ];
    }
}

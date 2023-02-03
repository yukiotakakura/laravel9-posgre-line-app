<?php

namespace Database\Factories;

use App\Models\LineLoginChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LineLoginChannel>
 */
class LineLoginChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'channel_id' => 1657765317,
            'name' => $this->faker->name(),
            'channel_secret' => 'a8b64aa4d0722fa2851a054d70f6d10b',
        ];
    }
}

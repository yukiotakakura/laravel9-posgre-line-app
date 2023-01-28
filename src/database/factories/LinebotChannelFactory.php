<?php

namespace Database\Factories;

use App\Models\LinebotChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LinebotChannel>
 */
class LinebotChannelFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = LinebotChannel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'channel_id' => 1657766598,
            'name' => $this->faker->name(),
            'channel_secret' => 'a82f1d09f2b7c7687812b940d9e0fdab',
            'access_token' => 'V2KWt/JUR5FQxvSVeBkxjHn/l2i255FNx6X9eZXN8krpxohpQNRiRZN44UNqxRKdyPBuQSQuOTfg1/zlcTnMn68nHmZYD6ESzWCs++ZfF5WfTQR2AVI70kevuix7uuBL+Gc2/b/mmDAa0aavIvfGRAdB04t89/1O/w1cDnyilFU=',
        ];
    }
}

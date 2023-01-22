<?php

namespace Database\Seeders;

use App\Models\LinebotChannel;
use App\Models\LinedevelopersProvider;
use Illuminate\Database\Seeder;

class LinedevelopersProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinedevelopersProvider::factory()
            ->has(LinebotChannel::factory()->count(1), 'linebotChannels')
            ->count(1)
            ->create()
        ;
    }
}

<?php

namespace Database\Seeders;

use App\Models\LinebotChannel;
use App\Models\LinedevelopersProvider;
use App\Models\LineloginChannel;
use Illuminate\Database\Seeder;

class LinedevelopersProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinedevelopersProvider::factory()
            ->has(LineloginChannel::factory()->count(1))
            ->has(LinebotChannel::factory()->count(1))
            ->count(1)
            ->create()
        ;
    }
}


<?php

namespace Database\Seeders;

use App\Models\LinedevelopersProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinedevelopersProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        LinedevelopersProvider::factory()->count(1)->create();
    }
}

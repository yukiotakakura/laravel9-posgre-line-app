<?php

namespace Database\Seeders;

use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
//        DB::table('teams')->insert([
//            [
//                'user_id' => 1,
//                'name' => 'チーム名',
//                'personal_team' => true,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
//            ],
//        ]);
    }
}

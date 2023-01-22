<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
//        DB::table('users')->insert([
//            [
//                'name' => 'Tanaka Taro',
//                'email' => Str::random(10).'.com',
//                'password' => Hash::make('password'),
//                'email_verified_at' => now(),
//                'current_team_id' => 1,
//
//            ],
//        ]);

        // TODO factoryが実行されない原因調査
        User::factory(10)->create();
    }
}

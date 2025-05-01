<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        User::create([
            'name' => env('MAIN_ADMIN_NAME'),
            'email' => env('MAIN_ADMIN_EMAIL'),
            'password' => bcrypt(env('MAIN_ADMIN_PASSWORD')),
            'is_approved' => true,
            'is_active' => true

        ])->assignRole('admin');

        //User::factory(10)->withPersonalTeam()->create();
    }
}

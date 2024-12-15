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
        User::create([
            'name' => 'Idequel Bernabel',
            'email' => 'id.bernabel@gmail.com',
            'password' => bcrypt('Zd/sK3iD/u53/QM'),
        ])->assignRole('admin');
    }
}

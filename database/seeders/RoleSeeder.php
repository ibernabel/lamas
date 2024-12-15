<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'broker']);
        Role::create(['name' => 'promoter']);
        Role::create(['name' => 'analyst']);
        Role::create(['name' => 'secretary']);
        Role::create(['name' => 'trainee']);
        Role::create(['name' => 'legal']);
        Role::create(['name' => 'collector']);

    }
}

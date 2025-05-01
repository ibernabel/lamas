<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $admin = Role::findByName('admin');
      $manager = Role::findByName('manager');
      $supervisor = Role::findByName('supervisor');
      $broker = Role::findByName('broker');

      Permission::create(['name' => 'dashboard'])->syncRoles([$admin, $manager, $supervisor, $broker]);

      Permission::create(['name' => 'application.create'])->syncRoles([$admin, $manager, $supervisor, $broker]);
      Permission::create(['name' => 'application.edit'])->syncRoles([$admin, $manager, $supervisor, $broker]);
      Permission::create(['name' => 'application.index'])->syncRoles([$admin, $manager, $supervisor, $broker]);
      Permission::create(['name' => 'application.delete'])->syncRoles([$admin, $manager, $supervisor, $broker]);

      Permission::create(['name' => 'user.create'])->syncRoles([$admin, $manager]);
      Permission::create(['name' => 'user.edit'])->syncRoles([$admin, $manager]);
      Permission::create(['name' => 'user.index'])->syncRoles([$admin, $manager]);
      Permission::create(['name' => 'user.delete'])->syncRoles([$admin, $manager]);
      Permission::create(['name' => 'users.manage'])->syncRoles([$admin, $manager]);

    }
}

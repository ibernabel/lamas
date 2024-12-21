<?php

namespace Database\Seeders;

use App\Models\Broker;
use App\Models\Portfolio;
use App\Models\Promoter;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
          RoleSeeder::class,
          PermissionSeeder::class,
          UserSeeder::class,
          BrokerSeeder::class,
          PortfolioSeeder::class,
          CustomerSeeder::class,
          PromoterSeeder::class,
        ]);


        // User::factory(10)->withPersonalTeam()->create();


    }
}

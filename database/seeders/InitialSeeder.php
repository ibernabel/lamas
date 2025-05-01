<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InitialSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CreditRiskCategorySeeder::class,
            CreditRiskSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            BrokerSeeder::class,
            PortfolioSeeder::class,

        ]);


        // User::factory(10)->withPersonalTeam()->create();


    }
}

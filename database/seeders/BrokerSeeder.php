<?php

namespace Database\Seeders;

use App\Models\Broker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrokerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Broker::create([
            'broker_seniority' => 'Senior',
            'user_id' => 1

        ]);
        //Broker::create([
        //    'broker_seniority' => 'Mid-senior',
        //    'user_id' => 2
        //]);
        //Broker::create([
        //    'broker_seniority' => 'Junior',
        //    'user_id' => 3
        //]);
        //Broker::create([
        //    'broker_seniority' => 'Trainee',
        //    'user_id' => 4
        //]);
        //Broker::create([
        //    'broker_seniority' => 'Mid-senior',
        //    'user_id' => 5
        //]);
    }
}

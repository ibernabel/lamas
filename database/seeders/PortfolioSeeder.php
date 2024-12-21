<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Portfolio::create([
            'name' => 'Portfolio 1',
            'broker_id' => 1
        ]);
        Portfolio::create([
            'name' => 'Portfolio 2',
            'broker_id' => 2
        ]);
        Portfolio::create([
            'name' => 'Portfolio 3',
            'broker_id' => 3
        ]);
        Portfolio::create([
            'name' => 'Portfolio 4',
            'broker_id' => 4
        ]);
        Portfolio::create([
            'name' => 'Portfolio 5',
            'broker_id' => 5
        ]);
    }
}

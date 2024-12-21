<?php

namespace Database\Seeders;

use App\Models\Promoter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoterSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Promoter::factory(10)->create();
  }
}

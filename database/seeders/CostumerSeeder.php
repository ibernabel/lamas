<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Costumer;
use App\Models\CostumerDetail;
use Database\Factories\CostumerFinancialInfoFactory;
use Database\Factories\CostumerJobInfoFactory;
use Database\Factories\CostumerReferenceInfoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Costumer::factory(50)->create();
      CostumerDetail::factory(50)->create();
      CostumerFinancialInfoFactory::factory(50)->create();
      CostumerJobInfoFactory::factory(50)->create();
      CostumerReferenceInfoFactory::factory(100)->create();
      Company::factory(50)->create();
    }
}

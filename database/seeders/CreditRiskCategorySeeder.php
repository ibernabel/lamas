<?php

namespace Database\Seeders;

use App\Models\CreditRiskCategory;
use Illuminate\Database\Seeder;

class CreditRiskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      CreditRiskCategory::create(
        [
          'name' => 'account movements',
          'description' => 'Risks related to account transactions, internet banking',
        ]);
        CreditRiskCategory::create(
        [
         'name' => 'banking and credit history',
          'description' => 'Risks related to credit score',
        ]);
        CreditRiskCategory::create(
        [
         'name' => 'personal/family situation',
          'description' => 'Risks related to family nucleus',
        ]);
        CreditRiskCategory::create(
        [
         'name' => 'employment',
          'description' => 'Risks related to job stability',
        ]);
        CreditRiskCategory::create(
        [
         'name' => 'financial status',
          'description' => 'Risks related to solvency and indebtedness',
        ]);
        CreditRiskCategory::create(
        [
         'name' => 'payment capacity and morale',
          'description' => 'Risks related to payment capacity and morale to pay',
        ]);
        CreditRiskCategory::create(
        [
         'name' => 'guarantees',
          'description' => 'Risks related to Documentation/Process Issues',
        ]
      );
    }
}

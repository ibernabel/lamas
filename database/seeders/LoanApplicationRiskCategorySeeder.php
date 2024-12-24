<?php

namespace Database\Seeders;

use App\Models\LoanApplicationRiskCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanApplicationRiskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      LoanApplicationRiskCategory::create(
        [
          'name' => 'account movements',
          'description' => 'Risks related to account transactions, internet banking',
        ]);
      LoanApplicationRiskCategory::create(
        [
         'name' => 'banking and credit history',
          'description' => 'Risks related to credit score',
        ]);
      LoanApplicationRiskCategory::create(
        [
         'name' => 'personal/family situation',
          'description' => 'Risks related to family nucleus',
        ]);
      LoanApplicationRiskCategory::create(
        [
         'name' => 'employment',
          'description' => 'Risks related to job stability',
        ]);
      LoanApplicationRiskCategory::create(
        [
         'name' => 'financial status',
          'description' => 'Risks related to solvency and indebtedness',
        ]);
      LoanApplicationRiskCategory::create(
        [
         'name' => 'payment capacity and morale',
          'description' => 'Risks related to payment capacity and morale to pay',
        ]);
      LoanApplicationRiskCategory::create(
        [
         'name' => 'guarantees',
          'description' => 'Risks related to Documentation/Process Issues',
        ]
      );
    }
}

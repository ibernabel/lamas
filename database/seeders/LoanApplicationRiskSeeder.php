<?php

namespace Database\Seeders;

use App\Models\LoanApplicationRisk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanApplicationRiskSeeder extends Seeder
{

  /**
   * Risk category IDs
   */
  private const RISK_CATEGORIES = [
    'ACCOUNT_MOVEMENTS' => 1,
    'BANKING_AND_CREDIT_HISTORY' => 2,
    'PERSONAL_FAMILY_SITUATION' => 3,
    'EMPLOYMENT' => 4,
    'FINANCIAL_STATUS' => 5,
    'PAYMENT_CAPACITY_AND_MORALE' => 6,
    'GUARANTEES' => 7,
  ];

  /**
   * Run the database seeds.
   * @return void
   * @throws \Exception
   */
  public function run(): void
  {
    try {
      DB::beginTransaction();

      $risks = $this->getRiskData();

      // Batch insert instead of multiple single inserts
      foreach (array_chunk($risks, 50) as $chunk) {
        LoanApplicationRisk::insert($chunk);
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
  /**
   * Get the risk data organized by categories
   *
   * @return array
   */
  private function getRiskData(): array
  {
    return [
      // Banking Behavior Risks
      [
        "risk" => "Quick Withdrawal Pattern",
        "description" => "According to states it is observed that he withdraws very fast",
        "risk_category_id" => self::RISK_CATEGORIES['ACCOUNT_MOVEMENTS'],
      ],
      [
        "risk" => "Experienced Quick Withdrawal Risk",
        "description" => "Because of his ease of experience and/or freedom he can withdraw fast",
        "risk_category_id" => self::RISK_CATEGORIES['ACCOUNT_MOVEMENTS'],
      ],
      [
        "risk" => "Prestamista Banking Presence",
        "description" => "In internet banking there are prestamitas added",
        "risk_category_id" => self::RISK_CATEGORIES['ACCOUNT_MOVEMENTS'],
      ],
      [
        "risk" => "Banking Misuse",
        "description" => "Prestamita or other uses his internetbanking",
        "risk_category_id" => self::RISK_CATEGORIES['ACCOUNT_MOVEMENTS'],
      ],
      [
        "risk" => "Paycheck Bank Mismatch",
        "description" => "The bank where you get your paycheck is not in InfyCredit",
        "risk_category_id" => self::RISK_CATEGORIES['ACCOUNT_MOVEMENTS'],
      ],
      [
        "risk" => "Poor Bank Loan History",
        "description" => "Bad loan history with banks",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Poor General Loan History",
        "description" => "Bad loan history",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Poor Credit Card History",
        "description" => "Bad credit card history",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Poor Service Payment History",
        "description" => "Bad history in telecom and services",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "No Credit History",
        "description" => "No credit experience in buro",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Multiple Active Debts",
        "description" => "Many active debts",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Arrears in Active Debts",
        "description" => "Active debts with arrears",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Debt Data Mismatch",
        "description" => "Disclarified debts do not match with Data Credito",
        "risk_category_id" => self::RISK_CATEGORIES['BANKING_AND_CREDIT_HISTORY'],
      ],
      [
        "risk" => "Dependent Adult",
        "description" => "Client is single, lives with parents, over 25",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Informal Marital Status",
        "description" => "Informal/non-existent marital relationship",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "No Family Unit",
        "description" => "Unmarried with no children in the relationship",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "External Dependents",
        "description" => "Has children outside the family unit",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Rental Housing",
        "description" => "Lives in rented house",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Recent Relocation",
        "description" => "Newly moved in house and/or sector",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Shared Household",
        "description" => "More than one family lives in the household",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Dependent Spouse",
        "description" => "Spouse without income/salary",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Incomplete Household Information",
        "description" => "Does not provide information and phone number for spouse or other household members",
        "risk_category_id" => self::RISK_CATEGORIES['PERSONAL_FAMILY_SITUATION'],
      ],
      [
        "risk" => "Short Employment Duration",
        "description" => "Less than one year on the job",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "No Career Growth",
        "description" => "Client has not moved up in position over time",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "Minimum Wage Income",
        "description" => "Earns minimum wage",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "Poor Employer Communication",
        "description" => "No ease of communication with company and/or immediate boss",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "Missing Employment Verification",
        "description" => "No job letter or confirmation of position",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "Difficult Job Access",
        "description" => "Difficult access to your job position",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "Group Formation Difficulty",
        "description" => "Lone Client difficult to form group",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "No Work Contact",
        "description" => "No access to work phone/call",
        "risk_category_id" => self::RISK_CATEGORIES['EMPLOYMENT']
      ],
      [
        "risk" => "Over-indebted Status",
        "description" => "You are over-indebted according to calculated margins",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "Payment Difficulties",
        "description" => "Have difficulties with loan and/or credit card payments",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "Increasing Debt Pattern",
        "description" => "Have increased your debts in the last 2 years",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "High Active Debts",
        "description" => "Many active debts at present",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "Low Collateral",
        "description" => "Few collateral/accumulated loans",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "No Assets",
        "description" => "Don't own household assets",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "No Vehicle",
        "description" => "No vehicle or motor of their own",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "High Loan Request",
        "description" => "Requests very high loan amount",
        "risk_category_id" => self::RISK_CATEGORIES['FINANCIAL_STATUS']
      ],
      [
        "risk" => "High Margin Risk",
        "description" => "Risky in margin Quota/available, more than 50%",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "Single Income Dependency",
        "description" => "Depends on a single income",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "Multiple Dependents",
        "description" => "Have many dependents",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "High Expenses",
        "description" => "You have many expenses for which you are responsible",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "High Proposed Fee",
        "description" => "The fee you are proposing is too high",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "No Payment History",
        "description" => "Data does not disclose history to qualify payment morale",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "No Reference Information",
        "description" => "With the references, no information was gathered about your payment morale",
        "risk_category_id" => self::RISK_CATEGORIES['PAYMENT_CAPACITY_AND_MORALE']
      ],
      [
        "risk" => "Benefit Collection Issues",
        "description" => "Difficulty in collecting benefits",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "Guarantor Required",
        "description" => "Loan Requires Guarantor",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "Collateral Required",
        "description" => "Requires collateral",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "No Internet Banking",
        "description" => "No internet banking available",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "Benefits Contract Issue",
        "description" => "I do not apply for signing benefits contract",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "Poor References",
        "description" => "No or bad personal references",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "No Equipment Ownership",
        "description" => "The furniture/equipment used is not my own",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "No Mail Access",
        "description" => "No access to client's mail",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "No Investment Plan",
        "description" => "Did not specify investment plan",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
      [
        "risk" => "Risky Loan Usage",
        "description" => "Risky plan to use loan for",
        "risk_category_id" => self::RISK_CATEGORIES['GUARANTEES'],
      ],
    ];
  }
}

/**
 * Key improvements made:

 * Batch Processing : Instead of creating records one by one, we now use chunks to insert multiple records at once, which is more efficient for large datasets.

 * Transaction Management : Added database transaction handling to ensure data integrity. If any insert fails, all changes will be rolled back.

 * Constants : Added category constants to make the code more maintainable and reduce magic numbers.

 * Separation of Concerns : Moved the risk data into a separate method for better organization and maintainability.

 * Error Handling : Added try-catch block to handle potential database errors gracefully.

 * Documentation : Added PHPDoc blocks to improve code documentation.

 * To complete the refactoring, you would need to move all the risk data into the getRiskData() method, organized by category. Here's an example of how to structure a portion of it:
 * This refactored version:

 * Is more maintainable

 * Has better performance due to batch processing

 * Is more robust with error handling

 * Is better documented

 * Uses constants instead of magic numbers

 * Is easier to modify in the future
 * 
 */

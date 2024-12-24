<?php

namespace Database\Seeders;

use App\Models\LoanApplicationRisk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanApplicationRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoanApplicationRisk::create([
            "risk" => "Quick Withdrawal Pattern",
            "description" => "According to states it is observed that he withdraws very fast",
            "risk_category_id" => 1,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Experienced Quick Withdrawal Risk",
            "description" => "Because of his ease of experience and/or freedom he can withdraw fast",
            "risk_category_id" => 1,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Prestamista Banking Presence",
            "description" => "In internet banking there are prestamitas added",
            "risk_category_id" => 1,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Banking Misuse",
            "description" => "Prestamita or other uses his internetbanking",
            "risk_category_id" => 1,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Paycheck Bank Mismatch",
            "description" => "The bank where you get your paycheck is not in InfyCredit",
            "risk_category_id" => 1,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Poor Bank Loan History",
            "description" => "Bad loan history with banks",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Poor General Loan History",
            "description" => "Bad loan history",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Poor Credit Card History",
            "description" => "Bad credit card history",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Poor Service Payment History",
            "description" => "Bad history in telecom and services",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Credit History",
            "description" => "No credit experience in buro",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Multiple Active Debts",
            "description" => "Many active debts",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Arrears in Active Debts",
            "description" => "Active debts with arrears",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Debt Data Mismatch",
            "description" => "Disclarified debts do not match with Data Credito",
            "risk_category_id" => 2,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Dependent Adult",
            "description" => "Client is single, lives with parents, over 25",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Informal Marital Status",
            "description" => "Informal/non-existent marital relationship",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Family Unit",
            "description" => "Unmarried with no children in the relationship",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "External Dependents",
            "description" => "Has children outside the family unit",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Rental Housing",
            "description" => "Lives in rented house",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Recent Relocation",
            "description" => "Newly moved in house and/or sector",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Shared Household",
            "description" => "More than one family lives in the household",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Dependent Spouse",
            "description" => "Spouse without income/salary",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Incomplete Household Information",
            "description" => "Does not provide information and phone number for spouse or other household members",
            "risk_category_id" => 3,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Short Employment Duration",
            "description" => "Less than one year on the job",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Career Growth",
            "description" => "Client has not moved up in position over time",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Minimum Wage Income",
            "description" => "Earns minimum wage",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Poor Employer Communication",
            "description" => "No ease of communication with company and/or immediate boss",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Missing Employment Verification",
            "description" => "No job letter or confirmation of position",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Difficult Job Access",
            "description" => "Difficult access to your job position",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Group Formation Difficulty",
            "description" => "Lone Client difficult to form group",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Work Contact",
            "description" => "No access to work phone/call",
            "risk_category_id" => 4,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Over-indebted Status",
            "description" => "You are over-indebted according to calculated margins",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Payment Difficulties",
            "description" => "Have difficulties with loan and/or credit card payments",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Increasing Debt Pattern",
            "description" => "Have increased your debts in the last 2 years",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "High Active Debts",
            "description" => "Many active debts at present",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Low Collateral",
            "description" => "Few collateral/accumulated loans",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Assets",
            "description" => "Don't own household assets",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Vehicle",
            "description" => "No vehicle or motor of their own",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "High Loan Request",
            "description" => "Requests very high loan amount",
            "risk_category_id" => 5,
        ]);

        LoanApplicationRisk::create([
            "risk" => "High Margin Risk",
            "description" => "Risky in margin Quota/available, more than 50%",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Single Income Dependency",
            "description" => "Depends on a single income",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Multiple Dependents",
            "description" => "Have many dependents",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "High Expenses",
            "description" => "You have many expenses for which you are responsible",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "High Proposed Fee",
            "description" => "The fee you are proposing is too high",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Payment History",
            "description" => "Data does not disclose history to qualify payment morale",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Reference Information",
            "description" => "With the references, no information was gathered about your payment morale",
            "risk_category_id" => 6,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Benefit Collection Issues",
            "description" => "Difficulty in collecting benefits",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Guarantor Required",
            "description" => "Loan Requires Guarantor",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Collateral Required",
            "description" => "Requires collateral",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Internet Banking",
            "description" => "No internet banking available",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Benefits Contract Issue",
            "description" => "I do not apply for signing benefits contract",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Poor References",
            "description" => "No or bad personal references",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Equipment Ownership",
            "description" => "The furniture/equipment used is not my own",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Mail Access",
            "description" => "No access to client's mail",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "No Investment Plan",
            "description" => "Did not specify investment plan",
            "risk_category_id" => 7,
        ]);

        LoanApplicationRisk::create([
            "risk" => "Risky Loan Usage",
            "description" => "Risky plan to use loan for",
            "risk_category_id" => 7,
        ]);
    }
}

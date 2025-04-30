<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\CreditRisk;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\CustomerFinancialInfo;
use App\Models\CustomerJobInfo;
use App\Models\CustomerReference;
use App\Models\LoanApplication;
use App\Models\LoanApplicationDetail;
use App\Models\LoanApplicationNote;
use App\Models\CustomerVehicle;
use App\Models\Phone;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    private const CUSTOMER_COUNT = 50;
    private const PHONE_COUNT = 2;
    private const REFERENCE_COUNT = 2;
    private const LOAN_APPLICATION_NOTES_COUNT = 3;
    private const CREDIT_RISK_COUNT = 5;



    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCustomersWithRelations();
    }

    /**
     * Create customers with all their related data
     */
    private function createCustomersWithRelations(): void
    {
        Customer::factory(self::CUSTOMER_COUNT)
            ->has($this->createCustomerDetails(), 'details')
            ->has(CustomerFinancialInfo::factory(), 'financialInfo')
            ->has(CustomerJobInfo::factory(), 'jobInfo')
            ->has($this->createReferences(), 'references')
            ->has(Company::factory(), 'company')
            ->has($this->createLoanApplication(), 'loanApplications')
            ->has(CustomerVehicle::factory()->count(1), 'vehicles')
            ->create();
    }

    /**
     * Create customer details with phones and addresses
     */
    private function createCustomerDetails()
    {
        return CustomerDetail::factory()
            ->has(Phone::factory()->count(self::PHONE_COUNT), 'phones')
            ->has(Address::factory(), 'addresses');
    }
    /**
     * Create Loan Application and details for Customer
     */
    private function createLoanApplication()
    {
        return LoanApplication::factory()
            ->has(LoanApplicationDetail::factory(), 'details')
            ->has(LoanApplicationNote::factory()->count(self::LOAN_APPLICATION_NOTES_COUNT), 'notes')
            ->has(CreditRisk::factory()->count(self::CREDIT_RISK_COUNT), 'credit_risk_loan_application');
    }
    /**
     * Create References for Customer
     */
    private function createReferences()
    {
        return CustomerReference::factory()->count(self::REFERENCE_COUNT)
        ->has(Phone::factory()->count(self::PHONE_COUNT), 'phones')
        ->has(Address::factory(), 'addresses');
    }
}

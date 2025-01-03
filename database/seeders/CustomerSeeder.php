<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\CustomerFinancialInfo;
use App\Models\CustomerJobInfo;
use App\Models\CustomerReference;
use App\Models\LoanApplication;
use App\Models\LoanApplicationDetail;
use App\Models\LoanApplicationNote;
use App\Models\LoanApplicationRisk;
use App\Models\Phone;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    private const CUSTOMER_COUNT = 20;
    private const PHONE_COUNT = 2;
    private const REFERENCE_COUNT = 2;
    private const LOAN_APPLICATION_NOTES_COUNT = 3;


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
            ->has(CustomerReference::factory()->count(self::REFERENCE_COUNT), 'references')
            ->has(Company::factory(), 'company')
            ->has($this->createLoanApplication(), 'loanApplications')
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
     * Create LOan Application and details for Customer
     */
    private function createLoanApplication()
    {
        return LoanApplication::factory()
            ->has(LoanApplicationDetail::factory(), 'details')
            ->has(LoanApplicationNote::factory()->count(self::LOAN_APPLICATION_NOTES_COUNT), 'notes');
    }
}

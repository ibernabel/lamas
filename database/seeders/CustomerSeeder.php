<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\CustomerFinancialInfo;
use App\Models\CustomerJobInfo;
use App\Models\CustomerReference;
use App\Models\Phone;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Customer::factory(50)
      ->has(
        CustomerDetail::factory()
          ->has(Phone::factory()->count(2), 'phones')
          ->has(Address::factory(), 'addresses')
        , 'details'
      )
      ->has(CustomerFinancialInfo::factory(), 'financialInfo')
      ->has(CustomerJobInfo::factory(), 'jobInfo')
      ->has(CustomerReference::factory()->count(2), 'references')
      ->has(Company::factory(), 'company')  // Added relationship name
      ->create();
  }
}

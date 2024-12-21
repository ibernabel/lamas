<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFinancialInfo extends Model
{
  use HasFactory;

  protected $table = 'customer_financial_info';

  protected $fillable = [
    'customer_id',
    'other_incomes',
    'discounts',
    'housing_type',
    'monthly_housing_payment',
    'total_debts',
    'loan_installments',
    'household_expenses',
    'labor_benefits',
    'guarantee_assets',
    'total_incomes',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
}

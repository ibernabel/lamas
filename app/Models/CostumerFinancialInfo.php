<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumerFinancialInfo extends Model
{
    protected $table = 'costumer_financial_info';

    protected $fillable = [
        'costumer_id',
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

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }
}

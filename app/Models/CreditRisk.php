<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CreditRisk extends Model
{
    use HasFactory;

    protected $table = 'credit_risk_loan_application';

    protected $fillable = [
        'risk_category_id',
        'risk',
        'description',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function loanApplication(): BelongsToMany
    {
        return $this->belongsToMany(LoanApplication::class);
    }
    public function riskCategory(): BelongsTo
    {
        return $this->belongsTo(CreditRiskCategory::class);
    }
}

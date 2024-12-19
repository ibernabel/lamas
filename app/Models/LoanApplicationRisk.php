<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplicationRisk extends Model
{
    protected $fillable = [
        'loan_application_id',
        'risk',
        'description',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}

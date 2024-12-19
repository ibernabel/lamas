<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplicationDetail extends Model
{
    protected $fillable = [
      'loan_application_id',
      'amount',
      'term',
      'rate',
      'quota',
      'frecuency',
      'purpose',
      'costumer_comment'

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

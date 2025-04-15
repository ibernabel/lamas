<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationDetail extends Model
{
  use HasFactory;
  
  protected $fillable = [
    'loan_application_id',
    'amount',
    'term',
    'rate',
    'quota',
    'frequency', // Corrected typo
    'purpose',
    'customer_comment'

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

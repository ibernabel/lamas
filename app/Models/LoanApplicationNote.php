<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationNote extends Model
{
  use HasFactory;
  
  protected $table = 'loan_application_notes';

  protected $fillable = [
    'note',
    'user_id',
    'loan_application_id',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function loanApplication()
  {
    return $this->belongsTo(LoanApplication::class);
  }
}

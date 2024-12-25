<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
  use HasFactory;

  protected $table = 'loan_applications';

  protected $fillable = [
    'customer_id',
    'status',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
  public function details()
  {
    return $this->hasOne(LoanApplicationDetail::class);
  }
  public function risks()
  {
    return $this->hasMany(CreditRisk::class);
  }
  public function notes()
  {
    return $this->hasMany(LoanApplicationNote::class);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }
  public function details(): HasOne
  {
    return $this->hasOne(LoanApplicationDetail::class);
  }
  public function creditRisks(): HasMany
  {
    return $this->hasMany(CreditRisk::class);
  }
  public function notes(): HasMany
  {
    return $this->hasMany(LoanApplicationNote::class);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationRiskCategory extends Model
{
  use HasFactory;
  
  protected $fillable = [
    'name',
    'description',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function risks()
  {
    return $this->hasMany(LoanApplicationRisk::class);
  }
}

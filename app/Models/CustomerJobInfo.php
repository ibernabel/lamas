<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerJobInfo extends Model
{
  use HasFactory;

  protected $table = 'customer_job_info';

  protected $fillable = [
    'is_self_employed',
    'role',
    'level',
    'start_date',
    'salary',
    'other_incomes',
    'other_incomes_source',
    'payment_type',
    'payment_frequency',
    'payment_bank',
    'payment_account_number',
    'schedule',
    'supervisor_name',
    'customer_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class, 'customer_id');
  }
}

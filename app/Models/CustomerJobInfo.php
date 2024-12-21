<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerJobInfo extends Model
{
  use HasFactory;

  protected $table = 'customer_job_info';

  protected $fillable = [
    'role',
    'level',
    'start_date',
    'salary',
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

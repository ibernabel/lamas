<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumerJobInfo extends Model
{
    protected $table = 'costumer_job_info';

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

      'costumer_id',
    ];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class, 'costumer_id');
    }
}

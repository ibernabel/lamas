<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersAccount extends Model
{
    use HasFactory;

    protected $table = 'customers_accounts';

    protected $fillable = [
        'customer_id',
        'number',
        'type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

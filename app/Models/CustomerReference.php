<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReference extends Model
{
  use HasFactory;

  protected $table = 'customer_references';

  protected $fillable = [
    'name',
    'email',
    'phone_number',
    'relationship',
    'is_who_referred',
    'customer_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
}

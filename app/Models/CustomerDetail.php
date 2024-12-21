<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
  use HasFactory;

  protected $table = 'customer_details';
  protected $fillable = [
    'customer_id',
    'first_name',
    'last_name',
    'email',
    'nickname',
    'birthday',
    'gender',
    'marital_status',
    'education_level',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
  ];
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
  public function phones()
  {
    return $this->morphMany(Phone::class, 'phoneable');
  }
  public function addresses()
  {
    return $this->morphMany(Address::class, 'addressable');
  }
}

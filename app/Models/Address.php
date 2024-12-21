<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use HasFactory;

  protected $fillable = [
    'street',
    'street2',
    'city',
    'state',
    'type',
    'postal_code',
    'country',
    'references',
  ];

  public function companies()
  {
    return $this->morphedByMany(Company::class, 'addressable');
  }
  public function customerDetails()
  {
    return $this->morphedByMany(CustomerDetail::class, 'addressable');
  }
}

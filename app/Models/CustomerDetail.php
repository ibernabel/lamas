<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerDetail extends Model
{
  use HasFactory;

  protected $table = 'customer_details';
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'nickname',
    'birthday',
    'gender',
    'marital_status',
    'education_level',
    'nationality',
    'housing_type',
    'housing_possession_type',
    'move_in_date',
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
  public function addresses(): MorphToMany
  {
    return $this->morphToMany(Address::class, 'addressable');
  }
  public function getVehicleDetails()
  {
    return $this->vehicle ? $this->vehicle->getVehicleDetails() : null;
  }

  public function setVehicleDetails($details)
  {
    if ($this->vehicle) {
      $this->vehicle->setVehicleDetails($details);
    }
  }
}

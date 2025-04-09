<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Company extends Model
{
  use HasFactory;
  
    protected $fillable = [
        'name',
        'email',
        'type',
        'website',
        'rnc',
        'departmet',
        'branch',
        'customer_id',

    ];

    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable');
    }
}

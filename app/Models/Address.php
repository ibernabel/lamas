<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
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
    public function costumerDetails()
    {
        return $this->morphedByMany(CostumerDetail::class, 'addressable');
    }
}

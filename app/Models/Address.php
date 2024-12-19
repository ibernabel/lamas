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

}

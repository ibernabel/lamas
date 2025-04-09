<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    protected $fillable = [
        'street',
        'street2',
        'city',
        'state',
        // other address fields
    ];

    /**
     * Get the parent addressable model.
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}

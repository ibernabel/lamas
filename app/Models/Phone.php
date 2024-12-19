<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'country_area',
        'number',
        'extension',
        'type',
        'phoneable_id',
        'phoneable_type',
    ];

    public function phoneable()
    {
        return $this->morphTo();
    }
}

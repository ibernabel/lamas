<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'email',
        'type',
        'website',
        'rnc',
        'departmet',
        'branch',
        'costumer_id',

    ];

    public function costumer()
    {
      return $this->belongsTo(Costumer::class, 'costumer_id');
    }
}

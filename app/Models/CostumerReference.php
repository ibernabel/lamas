<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumerReference extends Model
{
    protected $table = 'costumer_references';
    
    protected $fillable = [
      'name',
      'email',
      'phone_number',
      'relationship',
      'is_who_referred',
      'costumer_id',
    ];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }
}

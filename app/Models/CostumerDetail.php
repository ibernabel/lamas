<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumerDetail extends Model
{
    protected $table = 'costumer_details';
    protected $fillable = [
        'costumer_id',
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
    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

}

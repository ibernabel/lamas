<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promoter extends Model
{
    /** @use HasFactory<\Database\Factories\PromoterFactory> */
    use HasFactory;

    protected $fillable = [
        'NID',
        'bonus_type',
        'bonus_value',
        'bank_name',
        'bank_account_number',
        'bank_account_type',
        'bank_account_name',
        'user_id',

    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function costumers()
    {
        return $this->hasMany(Costumer::class);
    }
}

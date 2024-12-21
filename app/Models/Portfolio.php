<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    /** @use HasFactory<\Database\Factories\PortfolioFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        
    ];
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    /** @use HasFactory<\Database\Factories\CostumerFactory> */
    use HasFactory;

    protected $table = 'costumers';
    
    protected $fillable = [
        'NID',
        'is_referred',
        'referred_by',
        'lead_channel',
        'portfolio_id',
        'promoter_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }
    public function referred()
    {
        return $this->belongsTo(Costumer::class, 'referred_by');
    }
    public function referrals()
    {
        return $this->hasMany(Costumer::class, 'referred_by');
    }
    public function details()
    {
        return $this->hasOne(CostumerDetail::class);
    }
    public function references()
    {
        return $this->hasMany(CostumerReference::class);
    }
    public function financiaInfo()
    {
        return $this->hasOne(CostumerFinancialInfo::class);
    }
    public function jobInfo()
    {
        return $this->hasOne(CostumerJobInfo::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $table = 'customers';
    
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
        return $this->belongsTo(Customer::class, 'referred_by');
    }
    public function referrals()
    {
        return $this->hasMany(Customer::class, 'referred_by');
    }
    public function details()
    {
        return $this->hasOne(CustomerDetail::class);
    }
    public function references()
    {
        return $this->hasMany(CustomerReference::class);
    }
    public function financialInfo()
    {
        return $this->hasOne(CustomerFinancialInfo::class);
    }
    public function jobInfo()
    {
        return $this->hasOne(CustomerJobInfo::class);
    }
    public function company()
    {
      return $this->hasOne(Company::class);
    }
    public function loan()
    {
      return $this->hasMany(LoanApplication::class);
    }
}

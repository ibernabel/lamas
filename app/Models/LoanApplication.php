<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    protected $table = 'loan_applications';

    protected $fillable = [
        'costumer_id',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }
    public function details()
    {
        return $this->hasOne(LoanApplicationDetail::class);
    }
    public function risks()
    {
        return $this->hasMany(LoanApplicationRisk::class);
    }
    public function notes()
    {
        return $this->hasMany(LoanApplicationNote::class);
    }

}

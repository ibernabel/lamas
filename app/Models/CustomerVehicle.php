<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerVehicle extends Model
{
    use HasFactory;
    protected $table = 'customer_vehicles';
    protected $fillable = [
        'vehicle_type',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_year',
        'vehicle_color',
        'vehicle_plate_number',
        'is_financed',
        'is_owned',
        'is_leased',
        'is_rented',
        'is_shared',
    ];
    protected $hidden = [
      'created_at',
      'updated_at',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerVehicle extends Model
{
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
    
    public function customerDetail()
    {
        return $this->belongsTo(CustomerDetail::class);
    }
    public function getVehicleDetails()
    {
        return [
            'vehicle_type' => $this->vehicle_type,
            'vehicle_brand' => $this->vehicle_brand,
            'vehicle_model' => $this->vehicle_model,
            'vehicle_year' => $this->vehicle_year,
            'vehicle_color' => $this->vehicle_color,
            'vehicle_plate_number' => $this->vehicle_plate_number,
            'plate_number' => $this->vehicle_plate_number,
            'is_financed' => $this->is_financed,
            'is_owned' => $this->is_owned,
            'is_leased' => $this->is_leased,
            'is_rented' => $this->is_rented,
            'is_shared' => $this->is_shared,
        ];
    }
    public function setVehicleDetails($details)
    {
        $this->vehicle_type = $details['vehicle_type'] ?? $this->vehicle_type;
        $this->vehicle_brand = $details['vehicle_brand'] ?? $this->vehicle_brand;
        $this->vehicle_model = $details['vehicle_model'] ?? $this->vehicle_model;
        $this->vehicle_year = $details['vehicle_year'] ?? $this->vehicle_year;
        $this->vehicle_color = $details['vehicle_color'] ?? $this->vehicle_color;
        $this->vehicle_plate_number = $details['vehicle_plate_number'] ?? $this->vehicle_plate_number;
        $this->is_financed = $details['is_financed'] ?? $this->is_financed;
        $this->is_owned = $details['is_owned'] ?? $this->is_owned;
        $this->is_leased = $details['is_leased'] ?? $this->is_leased;
        $this->is_rented = $details['is_rented'] ?? $this->is_rented;
        $this->is_shared = $details['is_shared'] ?? $this->is_shared;
        $this->save();
    }
}

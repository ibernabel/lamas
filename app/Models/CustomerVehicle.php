<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerVehicle extends Model
{
    protected $fillable = [
        'customer_detail_id',
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

    public function customerDetail()
    {
        return $this->belongsTo(CustomerDetail::class);
    }
    public function getVehicleDetails()
    {
        return [
            'brand' => $this->vehicle_brand,
            'model' => $this->vehicle_model,
            'year' => $this->vehicle_year,
            'color' => $this->vehicle_color,
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
        $this->vehicle_brand = $details['brand'];
        $this->vehicle_model = $details['model'];
        $this->vehicle_year = $details['year'];
        $this->vehicle_color = $details['color'];
        $this->vehicle_plate_number = $details['plate_number'];
        $this->is_financed = $details['is_financed'] ?? $this->is_financed;
        $this->is_owned = $details['is_owned'] ?? $this->is_owned;
        $this->is_leased = $details['is_leased'] ?? $this->is_leased;
        $this->is_rented = $details['is_rented'] ?? $this->is_rented;
        $this->is_shared = $details['is_shared'] ?? $this->is_shared;
        $this->save();
    }
}

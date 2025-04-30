<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class CustomerReference extends Model
{
    use HasFactory;

    protected $table = 'customer_references';

    protected $fillable = [
        'name',
        'nid',
        'email',
        'relationship',
        'reference_since',
        'is_active',
        'occupation',
        'is_who_referred',
        'customer_id',
        'type',
        'address',
        'addresses',
        'phones',
        'phones.*.number',
        'phones.*.type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable');
    }
}

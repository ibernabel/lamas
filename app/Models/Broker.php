<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
  /** @use HasFactory<\Database\Factories\BrokerFactory> */
  use HasFactory;

  protected $fillable = [
    'name',
    'user_id',

  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function portfolios()
  {
    return $this->hasOne(Portfolio::class);
  }
}

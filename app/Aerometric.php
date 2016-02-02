<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aerometric extends Model
{
    protected $table = 'aerometrics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['station_id', 'temperature', 'pressure', 'altitude', 'insolation', 'humidity', 'co', 'co2', 'methane', 'butane', 'propane', 'benzene', 'ethanol', 'alcohol', 'hydrogen', 'ozone', 'cng', 'lpg', 'coal_gas', 'smoke', 'created_at'];

    protected $visible = ['temperature', 'pressure', 'altitude', 'insolation', 'humidity', 'co', 'co2', 'methane', 'butane', 'propane', 'benzene', 'ethanol', 'alcohol', 'hydrogen', 'ozone', 'cng', 'lpg', 'coal_gas', 'smoke', 'created_at'];

    public function station() {
        return $this->belongsTo(\App\Station::class, 'station_id');
    }

    public function scopeLatest($query) {
        return $query->orderBy('created_at', 'DESC');
    }
}

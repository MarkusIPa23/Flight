<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plane extends Model
{
    protected $fillable = [
    'icao24',
    'callsign',
    'origin_country',
    'longitude',
    'latitude',
    'baro_altitude',
    'velocity',
    'heading',
    'on_ground',
];

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorsData extends Model
{
    use HasFactory;
    protected $table='sensors_data';
    protected $fillable = [ 
        'name', 'type', 'date', 'hour', 'sensors_id', 'animal_id' 
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desease extends Model
{
    use HasFactory;
    protected $table='deseases';
    protected $fillable = [ 
        'description', 'type', 'cure', 'veterinary_id' 
    ];
}

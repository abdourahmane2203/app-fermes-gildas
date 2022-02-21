<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;
    protected $table='fonctions';
    protected $fillable = [ 
        'name', 'farm_id' 
    ];
}

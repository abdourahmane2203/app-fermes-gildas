<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Farm;

class Veterinary extends Model
{
    use HasFactory;
    protected $table='veterinaries';
    protected $fillable = [ 
        'name', 'phone' , 'email', 'address', 'password', 'region','profile',
    ];
    public function farms()
    {
        return $this->belongsToMany(Farm::class, 'veterinary_farm');
    }
}

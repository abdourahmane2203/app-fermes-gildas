<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table='employees';
    protected $fillable = [ 
        'name', 'phone' , 'address', 'email', 'region', 'salary', 'fonction_id', 'category_id', 'supervisor', 'password','contracttype_id', 'farm_id', 'profile',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeterinaryFarm extends Model
{
    use HasFactory;
    public $table = 'veterinary_farm';
   
    protected $fillable = [
        'veterinary_id','farm_id'
    ];
}

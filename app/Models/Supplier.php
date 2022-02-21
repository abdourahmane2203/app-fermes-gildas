<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Farm;
class Supplier extends Model
{
    use HasFactory;
    protected $table='suppliers';
    protected $fillable = [ 
        'name', 'region', 'description', 'address', 'phone', 'email'
    ];

    public function farms()
    {
        return $this->belongsToMany(Farm::class, 'supplier_farm');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\Veterinary;
class Farm extends Model
{
    use HasFactory;
    protected $table='farms';
    protected $fillable = [ 
        'name','phone', 'region', 'address', 'email', 'password_admin', 'herdside'
    ];

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_farm');
    }

    public function veterinaries()
    {
        return $this->belongsToMany(Veterinary::class, 'veterinary_farm');
    }
}

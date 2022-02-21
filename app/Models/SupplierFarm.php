<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierFarm extends Model
{
    use HasFactory;
   
    public $table = 'supplier_farm';
   
    protected $fillable = [
        'supplier_id','farm_id'
    ];
}

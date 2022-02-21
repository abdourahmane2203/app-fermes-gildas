<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table='posts';
    protected $fillable = [ 
        'category','title', 'image', 'description', 'type', 'like', 'user_id' 
    ];
}

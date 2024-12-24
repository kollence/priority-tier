<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'created_at',
        'updated_at'
    ];
}

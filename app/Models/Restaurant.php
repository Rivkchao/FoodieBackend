<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cuisine',
        'rating',
        'distance',
        'duration',
        'image',
        'badge',
    ];

    protected $casts = [
        'rating' => 'float',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'category',
        'category_id',
        'description',
        'price',
        'rating',
        'reviews',
        'image',
        'ingredients',
        'prepTime',
        'calories',
        'popular',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'reviews' => 'integer',
        'ingredients' => 'array',
        'popular' => 'boolean',
    ];

    public function categoryItem(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[\Illuminate\Database\Eloquent\Attributes\Appends([
    'category_name',
])]
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'description',
    'price',
    'stock',
    'status',
    'image',
    'category_id',
])]
final class Product extends Model
{
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'model');
    }

    protected function categoryName(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: fn () => $this->category->name);
    }
}

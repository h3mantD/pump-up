<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Override;

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
    'embedding',
])]
final class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return MorphMany<Review, $this>
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'model');
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function similarTo(Builder $query, string $text, int $limit = 30): Builder
    {
        return $query->whereVectorSimilarTo('embedding', $text, minSimilarity: 0.4)
            ->limit($limit);
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'embedding' => 'array',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string|null, never>
     */
    protected function categoryName(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: fn () => $this->category?->name);
    }
}

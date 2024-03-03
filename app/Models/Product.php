<?php

declare(strict_types=1);

namespace App\Models;

use Digikraaft\ReviewRating\Traits\HasReviewRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Product extends Model
{
    use HasFactory;
    use HasReviewRating;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

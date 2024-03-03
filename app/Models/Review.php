<?php

declare(strict_types=1);

namespace App\Models;

use Digikraaft\ReviewRating\Models\Review as ModelsReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Review extends ModelsReview
{
    use HasFactory;
}

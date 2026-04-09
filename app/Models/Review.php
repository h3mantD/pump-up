<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }
}

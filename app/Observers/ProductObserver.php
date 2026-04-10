<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\GenerateProductEmbedding;
use App\Models\Product;

final class ProductObserver
{
    public function saved(Product $product): void
    {
        if ($product->wasRecentlyCreated || $product->wasChanged(['name', 'description', 'category_id'])) {
            GenerateProductEmbedding::dispatch($product);
        }
    }
}

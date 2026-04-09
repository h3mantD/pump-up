<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\GenerateProductEmbedding;
use App\Models\Product;
use Illuminate\Console\Command;

final class GenerateProductEmbeddings extends Command
{
    protected $signature = 'products:generate-embeddings {--force : Regenerate all embeddings}';

    protected $description = 'Generate vector embeddings for products';

    public function handle(): int
    {
        $query = Product::query();

        if (! $this->option('force')) {
            $query->whereNull('embedding');
        }

        $count = $query->count();

        if (0 === $count) {
            $this->info('No products need embedding generation.');

            return self::SUCCESS;
        }

        $this->info("Dispatching embedding jobs for {$count} products...");

        $query->chunkById(100, function ($products): void {
            foreach ($products as $product) {
                GenerateProductEmbedding::dispatch($product);
            }
        });

        $this->info('All jobs dispatched.');

        return self::SUCCESS;
    }
}

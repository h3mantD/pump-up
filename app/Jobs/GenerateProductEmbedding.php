<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

final class GenerateProductEmbedding implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public Product $product) {}

    public function handle(): void
    {
        $text = implode(' ', array_filter([
            $this->product->name,
            $this->product->description,
            $this->product->category?->name,
        ]));

        $response = Embeddings::for([$text])
            ->dimensions(1536)
            ->generate(Lab::OpenAI, 'text-embedding-3-small');

        $this->product->updateQuietly([
            'embedding' => $response->embeddings[0],
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Codewithkyrian\ChromaDB\Concerns\HasChromaCollection;
use Codewithkyrian\ChromaDB\Contracts\ChromaModel;
use Codewithkyrian\ChromaDB\Embeddings\JinaEmbeddingFunction;
use Digikraaft\ReviewRating\Traits\HasReviewRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Product extends Model implements ChromaModel
{
    use HasChromaCollection;
    use HasFactory;
    use HasReviewRating;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'status',
        'image',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function documentFields(): array
    {
        return [
            'name',
            'category_id',
        ];
    }

    public function embeddingFunction(): JinaEmbeddingFunction
    {
        return new JinaEmbeddingFunction(config('chromadb.jina_api_key'));
    }

    public function collectionName(): string
    {
        return 'products_collection';
    }

    public function metadataFields(): array
    {
        return [
            'id',
            'name',
            'category_id',
        ];
    }

    public function toChromaMetadata(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Codewithkyrian\ChromaDB\Concerns\HasChromaCollection;
use Codewithkyrian\ChromaDB\Contracts\ChromaModel;
use Codewithkyrian\ChromaDB\Embeddings\HuggingFaceEmbeddingServerFunction;
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

    protected $appends = [
        'category_name',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    public function documentFields(): array
    {
        return [
            'name',
            'description',
            'category_id',
            'category_name',
        ];
    }

    public function embeddingFunction(): HuggingFaceEmbeddingServerFunction|JinaEmbeddingFunction
    {
        return new JinaEmbeddingFunction(config('chromadb.jina_api_key'));
        // return new HuggingFaceEmbeddingServerFunction(config("chromadb.hf_api_key"), config("chromadb.hf_model_name"));
    }

    public function collectionName(): string
    {
        return 'products_collection';
    }

    public function metadataFields(): array
    {
        return [
            'id',
            'description',
            'category_id',
            'category_name',
        ];
    }

    public function toChromaMetadata(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
        ];
    }
}

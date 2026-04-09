<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\StockStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

final class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $pageSize = $request->integer('page_size', 10);

        $products = Product::query();

        if ($request->has('ids')) {
            $products->whereIn('id', explode(',', $request->string('ids')->toString()));
        }
        if ($request->has('name')) {
            $products->where('name', 'like', '%' . $request->string('name')->toString() . '%');
        }
        if ($request->has('category_id')) {
            $products->where('category_id', $request->get('category_id'));
        }

        return response()->json($products->paginate($pageSize));
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(['product' => $product]);
    }

    public function getReviews(Product $product, Request $request): JsonResponse
    {
        $pageSize = $request->integer('page_size', 5);

        return response()->json($product->reviews()->paginate($pageSize));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $data['image_url'];
        }

        unset($data['image_url']);

        $product = Product::create($data);

        return response()->json(['product' => $product], 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $data['image_url'];
        }

        unset($data['image_url']);

        $product->update($data);

        return response()->json(['product' => $product->fresh()]);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    public function search(Request $request): JsonResponse
    {
        /** @var array{q: string, limit?: int} $validated */
        $validated = $request->validate([
            'q' => ['required', 'string'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:30'],
        ]);

        $limit = $validated['limit'] ?? 10;

        $products = Product::similarTo($validated['q'], $limit)->get();

        return response()->json(['data' => $products]);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        /** @var array{ids: array<int, int>} $validated */
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:products,id'],
        ]);

        $deleted = Product::whereIn('id', $validated['ids'])->delete();

        return response()->json(['deleted' => $deleted]);
    }

    public function bulkUpdateStock(Request $request): JsonResponse
    {
        /** @var array{products: array<int, array{id: int, stock: int}>} $validated */
        $validated = $request->validate([
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'integer', 'exists:products,id'],
            'products.*.stock' => ['required', 'integer', 'min:0'],
        ]);

        $updated = 0;
        foreach ($validated['products'] as $item) {
            Product::where('id', $item['id'])->update(['stock' => $item['stock']]);
            $updated++;
        }

        return response()->json(['updated' => $updated]);
    }

    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        /** @var array{products: array<int, array{id: int, status: string}>} $validated */
        $validated = $request->validate([
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'integer', 'exists:products,id'],
            'products.*.status' => ['required', Rule::enum(StockStatus::class)],
        ]);

        $updated = 0;
        foreach ($validated['products'] as $item) {
            Product::where('id', $item['id'])->update(['status' => $item['status']]);
            $updated++;
        }

        return response()->json(['updated' => $updated]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}

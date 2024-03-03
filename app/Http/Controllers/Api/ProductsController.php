<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $pageSize = $request->get('page_size', 10);

        $products = Product::query();

        if ($request->has('ids')) {
            $products->whereIn('id', explode(',', $request->get('ids')));
        }
        if ($request->has('name')) {
            $products->where('name', 'like', '%' . $request->get('name') . '%');
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
        $pageSize = $request->get('page_size', 5);

        return response()->json($product->reviews()->paginate($pageSize));
    }
}

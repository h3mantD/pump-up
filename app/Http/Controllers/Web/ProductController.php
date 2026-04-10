<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::query()->with('category');

        if ($request->filled('category_id')) {
            $products->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('name')) {
            $products->where('name', 'like', '%' . $request->string('name')->toString() . '%');
        }

        if ('available' === $request->input('status')) {
            $products->where('status', 'available');
        }

        return Inertia::render('Products/Index', [
            'products' => $products->paginate(12)->withQueryString(),
            'categories' => Category::all(),
            'filters' => $request->only(['category_id', 'name', 'status']),
        ]);
    }

    public function show(Product $product): Response
    {
        $product->load('category');

        $reviews = $product->reviews()->latest()->paginate(5);

        $relatedProducts = collect();

        try {
            $relatedProducts = Product::similarTo(
                $product->name . ' ' . $product->description,
                4
            )->where('id', '!=', $product->id)->with('category')->get();
        } catch (Throwable) {
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->with('category')
                ->limit(4)
                ->get();
        }

        return Inertia::render('Products/Show', [
            'product' => $product,
            'reviews' => $reviews,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}

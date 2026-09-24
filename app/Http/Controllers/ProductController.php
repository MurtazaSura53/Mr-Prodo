<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {}

    public function options()
    {
        $productOptions = $this->productService->options();
        return response()->json($productOptions);
    }
    public function index(Request $request)
    {
        $products = $this->productService->paginated();
        if ($request->expectsJson()) {
            return ProductResource::collection($products);
        }
        return view("products.index", compact('products'));
    }

    public function create()
    {
        $categories = Category::latest()->get(['id', 'name']);
        return view('products.create', compact('categories'));
    }
    public function store(StoreProductRequest $request)
    {
        $fields = $request->validated();
        $product = $this->productService->create($fields);
        return response()->json([
            'message' => "New Product Added",
            'data' => new ProductResource($product),
        ], 201);
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $product->load('category');
        $categories = Category::all(['id', 'name']);
        return view('products.edit', compact('categories', 'product'));
    }
    public function update(
        UpdateProductRequest $request,
        Product $product
    ) {
        $this->authorize('update', $product);
        $fields = $request->validated();
        $this->productService->update($product, $fields);
        return response()->json([
            'message' => 'Changes Saved',
            'data' => new ProductResource($product),
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->productService->delete($product);
        return response()->noContent();
    }
}

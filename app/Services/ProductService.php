<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function all()
    {
        return Product::with('category')->get();
    }
    public function paginated()
    {
        return Product::with('category')->paginate(10);
    }
    public function create(array $fields)
    {
        $fields['user_id'] = Auth::id();
        $product = Product::create($fields);
        $product->load('category');
        return $product;
    }
    public function update(Product $product, array $fields)
    {
        $product->update($fields);
    }
    public function delete(Product $product)
    {
        $product->delete();
    }
}

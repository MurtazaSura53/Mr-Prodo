<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    public function all(): Collection
    {
        return Category::withCount('products')->all();
    }

    public function paginated()
    {
        return Category::withCount('products')->paginate(10);
    }

    public function create(array $fields)
    {
        $fields['user_id'] = Auth::id();
        $category = Category::create($fields);
        $category->loadCount('products');
        return $category;
    }

    public function update(Category $category, array $fields)
    {
        $category->update($fields);
    }
    public function delete(Category $category)
    {
        $category->delete();
    }
}

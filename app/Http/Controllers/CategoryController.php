<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->paginated();
        if ($request->expectsJson()) {
            return CategoryResource::collection($categories);
        }
        return view('categories.index', compact('categories'));
    }
    public function store(
        StoreCategoryRequest $request,
    ) {
        $fields = $request->validated();
        $category = $this->categoryService->create($fields);
        return response()->json([
            "message" => "New Category Added",
            "data" => new CategoryResource($category),
        ], 201);
    }
    public function update(
        UpdateCategoryRequest $request,
        Category $category,
    ) {
        $this->authorize("update", $category);
        $this->categoryService->update($category, $request->validated());
        return response()->json([
            "message" => "Saved",
            "data" => new CategoryResource($category)
        ]);
    }
    public function destroy(Category $category)
    {
        $this->authorize("delete", $category);
        $this->categoryService->delete($category);
        return response()->noContent();
    }
}

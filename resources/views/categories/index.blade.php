@extends('layouts.master')

@section('meta')
<meta name="lastPage" content="{{ $categories->lastPage() }}">
@endsection

@section('title', 'Categories')

@section('styles')
@vite([
'resources/css/categories/index.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Categories</h1>
    <div class="btn-wrapper">
        <button type="button" class="btn-sml primary" id="addCategoryBtn"><i class="fa-solid fa-plus"></i>Add</button>
    </div>
</div>
<div class="page-overview-strip">
    <h3>Total Categories: <span id="totalItems">{{ $categories->total() }}</span></h3>
    <span>Showing: <span id="showingCategories">
            {{ $categories->firstItem() }} - {{ $categories->lastItem() }}
        </span></span>
</div>

<form id="form" class="categories-wrapper">
    @method('PATCH')
    @foreach($categories as $category)
    <div class="category">
        <div class="category-content">
            <div class="category-name-wrapper">
                <input class="category-name" type="text" name="category_name" data-category-id="{{$category->id}}" value="{{ $category->name }}">
            </div>

            <!-- <span class="category-name" data-category-name>{{$category->name}}</span> -->
            <span class="product_count" data-product-count>Total Products: {{ $category->products_count }}</span>
        </div>
        <div class="category-btn-wrapper">
            <button type="button" class="btn-icon error" data-delete-category data-category-id="{{$category->id}}"><i class="fa-solid fa-trash-can"></i></button>
        </div>
    </div>
    @endforeach
</form>


<div class="pagination-btn-wrapper">
    <button class="pagination-btn previous-btn" id="previousBtn">
        <i class="fa-solid fa-chevron-left"></i>
        Previous
    </button>

    <div class="pagination-pages" id="paginationPages"></div>

    <button class="pagination-btn next-btn" id="nextBtn">
        Next
        <i class="fa-solid fa-chevron-right"></i>
    </button>
</div>
@endsection

@section('scripts')
@vite([
'resources/js/categories/index.js',
])
@endsection
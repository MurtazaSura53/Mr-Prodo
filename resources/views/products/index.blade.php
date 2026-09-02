@extends('layouts.master')
@section('title', 'Products')

@section('meta')
<meta name="lastPage" content="{{ $products->lastPage() }}">
@endsection

@section('styles')
@vite([
'resources/css/products/index.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Products</h1>
    <div class="btn-wrapper">
        <button type="button" class="btn-sml primary" id="addProductBtn"><i class="fa-solid fa-plus"></i>Add</button>
    </div>
</div>
<div class="page-overview-strip">
    <h3>Total Products: <span id="totalItems">{{ $products->total() }}</span></h3>
    <span>Showing: <span id="showingProducts">
            {{ $products->firstItem() }} - {{ $products->lastItem() }}
        </span></span>
</div>
<form id="form" class="products-wrapper">
    @foreach($products as $product)
    <div class="product {{ ($product->stock > 0)?'in-stock':'out-of-stock'}}">
        <div class="product-content">
            <!-- Product Header -->
            <div class="product-header">
                <div class="product-title-wrapper">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <span class="product-category">{{ $product->category->name }}</span>
                </div>

                <div class="product-btn-wrapper">
                    <button type="button" class="btn-icon"
                        data-product-update
                        data-product-id="{{ $product->id}}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button type="button" class="btn-icon error"
                        data-product-delete
                        data-product-id="{{ $product->id }}">
                        <i class=" fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>


            <!-- Description -->
            <div class="product-description" data-product-description>
                <p class="description-text">
                    {{ $product->description }}
                </p>
                <button type="button" class="description-toggle">
                    <span>Show more</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
            </div>


            <!-- Product Information -->
            <div class="product-info">
                <div class="info-item">
                    <span class="info-label">Stock</span>
                    <strong class="info-value">{{ $product->stock}} {{ $product->unit }}</strong>
                </div>
                <div class="info-item">
                    <span class="info-label">Purchase Price</span>
                    <strong class="info-value">₹{{ $product->purchase_price }}</strong>
                </div>
                <div class="info-item">
                    <span class="info-label">Selling Price</span>
                    <strong class="info-value">₹{{ $product->selling_price }}</strong>
                </div>
            </div>
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
'resources/js/products/index.js',
])
@endsection
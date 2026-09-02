@extends('layouts.master')
@section('title', 'Edit Product')

@section('meta')
<meta name='product' content="{{ $product->id }}">
@endsection

@section('styles')
@vite([
'resources/css/products/create.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Edit Product</h1>
</div>

<form class="add-product-card" id="form">
    @csrf
    <div class="input-container">
        <div class="input-wrapper custom">
            <label for="name">Product Title</label>
            <input type="text" name="name" id="name" value="{{ $product->name }}" required>
            <span id="name_error"></span>
        </div>
        <div class="input-wrapper custom">
            <label for="category_id">Category</label>
            <select name="category_id" id="categoryId">
                @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{$product->category_id == $category->id ? 'selected' : ''}}>{{ $category->name }}</option>
                @endforeach
            </select>
            <span id="category_id_error"></span>
        </div>
        <div class="input-wrapper custom">
            <label for="unit">Stock Unit</label>
            <select name="unit" id="unit">
                @php
                $supportedUnits = [
                'pcs',
                'g',
                'kg',
                'ml',
                'ltr',
                'cm',
                'meter'
                ];
                @endphp
                @foreach($supportedUnits as $unit)
                <option value="{{ $unit }}" {{$product->unit == $unit ? 'selected' : ''}}>{{ $unit }}</option>
                @endforeach
            </select>
            <span id="unit_error"></span>
        </div>
        <div class="input-wrapper custom">
            <label for="stock">Stock</label>
            <input type="number" name="stock" value="{{ $product->stock }}" required>
            <span id="stock_error"></span>
        </div>
        <div class="input-wrapper custom">
            <label for="purchase_price">Purchase Price</label>
            <input type="number" name="purchase_price" id="purchase_price"
                value="{{ $product->purchase_price }}" required>

            <span id="purchase_price_error"></span>
        </div>
        <div class="input-wrapper custom">
            <label for="selling_price">Selling Price</label>
            <input type="number" name="selling_price" id="selling_price"
                value="{{ $product->selling_price }}" required>

            <span id="selling_price_error"></span>
        </div>
        <div class="input-wrapper" id="description-wrapper">
            <label for="description">Description</label>
            <textarea name="description" id="description">{{ $product->description ?? '' }}</textarea>
            <span id="description_error"></span>
        </div>
    </div>
    <div class="action-btn-wrapper">
        <button type="button" id="editProductBtn" class="primary btn-sml">Edit</button>
    </div>
</form>

@endsection

@section('scripts')
@vite([
'resources/js/products/edit.js',
])
@endsection
@extends('layouts.master')
@section('title', 'Add Purchase')

@section('styles')
@vite([
'resources/css/purchases/create.css',
])
@endsection

@section('main')
<form id="form">
    <div class="main-header">
        <h1>Add Purchase</h1>
    </div>
    <div class="input-wrapper-card">
        <div class="input-wrapper">
            <label for="supplier_name">Supplier</label>
            <input type="text" name="supplier_name" id="supplierName" required>
            <span id="supplier_name_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="purchase_date">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchaseDate" required>
            <span id="purchase_date_error"></span>
        </div>
    </div>
    <div class="purchase_items_card">
        <div class="card_header">
            <h2>Purchase Items</h2>
            <button type="button" id="addPurchaseItemBtn" class="btn-fit secondary"><i class="fa-solid fa-plus"></i>Add Item</button>
        </div>
        <div class="purchase-item-wrapper" id="purchaseItems">
            <!-- render items -->
        </div>
        <div class="total-amount-wrapper">
            <span class="total-amount-label">Total Amount: <strong id="totalAmount">₹0.00</strong></span>
        </div>
    </div>
    <div class="flex jc-end">
        <button type="button" id="storePurchaseBtn" class="btn primary">Create</button>
    </div>
</form>
<datalist id="productList">
    @foreach($products as $product)
    <option value="{{ $product->name }}"
        data-id="{{ $product->id }}"
        data-unit="{{ $product->unit }}"
        data-purchase-price="{{ $product->purchase_price}}"></option>
    @endforeach
</datalist>
@endsection

@section('scripts')
@vite([
'resources/js/purchases/create.js',
])
@endsection
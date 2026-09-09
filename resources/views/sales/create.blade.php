@extends('layouts.master')
@section('title', 'Add Sale')

@section('styles')
@vite([
'resources/css/purchases/create.css',
'resources/css/sales/create.css',
])
@endsection

@section('main')
<form id="form">
    <div class="main-header">
        <h1>Add Sale</h1>
    </div>
    <div class="input-wrapper-card">
        <div class="input-wrapper">
            <label for="customer_email">Customer Email</label>
            <input type="email" name="customer_email" id="customerEmail" required>
            <span id="customer_email_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="customer_name">Customer Name</label>
            <input type="text" name="customer_name" id="customerName" required>
            <span id="customer_name_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="customer_phone">Customer Phone</label>
            <input type="number" name="customer_phone" id="customerPhone" required>
            <span id="customer_phone_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="sale_date">Sale Date</label>
            <input type="date" name="sale_date" id="saleDate" required>
            <span id="sale_date_error"></span>
        </div>
    </div>

    <div class="purchase_items_card">
        <div class="card_header">
            <h2>Sale Items</h2>
            <button type="button" id="addSaleItemBtn" class="btn-fit secondary"><i class="fa-solid fa-plus"></i>Add Item</button>
        </div>
        <div class="purchase-item-wrapper" id="saleItems">
            <!-- render items -->

        </div>
        <div class="total-amount-wrapper">
            <span class="total-amount-label">Total Profit: <strong id="totalProfit">₹0.00</strong></span>
            <span class="total-amount-label">Total Amount: <strong id="totalAmount">₹0.00</strong></span>
        </div>
    </div>
    <div class="flex jc-end">
        <button type="button" id="storeSaleBtn" class="btn primary">Create</button>
    </div>
</form>
<datalist id="productList">
    @foreach($products as $product)
    <option value="{{ $product->name }}"
        data-id="{{ $product->id }}"
        data-unit="{{ $product->unit }}"
        data-sale-price="{{ $product->selling_price}}"
        data-purchase-price="{{ $product->purchase_price }}"></option>
    @endforeach
</datalist>
@endsection

@section('scripts')
@vite([
'resources/js/sales/create.js',
])
@endsection
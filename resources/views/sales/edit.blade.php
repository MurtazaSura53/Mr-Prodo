@extends('layouts.master')
@section('title', 'Edit Sale')

@section('meta')
<meta name="saleId" content="{{ $sale->id }}">
@endsection

@section('styles')
@vite([
'resources/css/purchases/create.css',
'resources/css/sales/create.css',
])
@endsection

@section('main')
<form id="form">
    <div class="main-header">
        <h1>Sale #{{ $sale->id }}</h1>
    </div>
    <div class="input-wrapper-card">
        <div class="input-wrapper">
            <label for="customer_email">Customer Email</label>
            <input type="email" name="customer_email" id="customerEmail" value="{{ $sale->customer?->email }}" required>
            <span id="customer_email_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="customer_name">Customer Name</label>
            <input type="text" name="customer_name" id="customerName" value="{{ $sale->customer?->name }}" required>
            <span id="customer_name_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="customer_phone">Customer Phone</label>
            <input type="number" name="customer_phone" id="customerPhone" value="{{ $sale->customer?->phone }}" required>
            <span id="customer_phone_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="sale_date">Sale Date</label>
            <input type="date" name="sale_date" id="saleDate" value="{{ $sale->sale_date }}" required>
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
            @php
            $saleItemsCount = 0;
            @endphp

            @foreach($sale->saleItems as $saleItem)
            <div class="sale-item-row" data-count="{{ $saleItemsCount }}">

                <div class="input-wrapper product-name">
                    <label for="product">Product</label>
                    <input
                        type="text"
                        name="sale_items[{{ $saleItemsCount }}]product_id"
                        list="productList"
                        autocomplete="off"
                        required="required"
                        value="{{ $saleItem->product->name }}"
                        data-product-id=""
                        data-id="{{ $saleItem->product->id }}">
                    <span id="sale_items[{{ $saleItemsCount }}]product_id_error"></span>
                </div>


                <div class="input-wrapper">
                    <label for="qty">Qty</label>
                    <div class="flex ai-end gap-thin">
                        <input
                            type="number"
                            name="sale_items[{{ $saleItemsCount }}]qty"
                            value="{{ $saleItem->qty }}"
                            required="required"
                            data-qty="">

                        <span class="unit">{{ $saleItem->unit }}</span>
                    </div>
                    <span id="sale_items[{{ $saleItemsCount }}]qty_error"></span>
                </div>


                <div class="input-wrapper">
                    <label for="price">Price</label>
                    <input
                        type="number"
                        name="sale_items[{{ $saleItemsCount }}]price"
                        value="{{ $saleItem->price }}"
                        required="required"
                        data-price="">
                </div>

                <input
                    type="hidden"
                    value="{{ $saleItem->product->purchase_price }}"
                    data-purchase-price>

                <div class="input-wrapper">
                    <label for="profit">Profit</label>
                    <input
                        type="number"
                        name="sale_items[{{ $saleItemsCount }}]profit"
                        class="profit"
                        value="{{ $saleItem->profit }}"
                        required="required"
                        readonly="true"
                        data-profit="">
                </div>

                <div class="input-wrapper">
                    <label for="subtotal">Subtotal</label>
                    <input
                        type="number"
                        name="sale_items[{{ $saleItemsCount }}]subtotal"
                        class="subtotal"
                        value="{{ $saleItem->subtotal }}"
                        required="required"
                        readonly="true"
                        data-subtotal="">
                </div>

                <div class="x-icon-wrapper">
                    <button
                        type="button"
                        class="btn-icon x-icon"
                        data-remove-btn>
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            @php
            $saleItemsCount++;
            @endphp
            @endforeach
            <input type="hidden" name="saleItemsCount" id="saleItemsCount" value="{{ $saleItemsCount }}">
        </div>
        <div class="total-amount-wrapper">
            <span class="total-amount-label">Total Profit: <strong id="totalProfit">₹{{ $sale->total_profit ?? 0.00}}</strong></span>
            <span class="total-amount-label">Total Amount: <strong id="totalAmount">₹{{ $sale->total_amount ?? 0.00 }}</strong></span>
        </div>
    </div>
    <div class="flex jc-end">
        <button type="button" id="deleteSaleBtn" class="btn error">Delete</button>
        <button type="button" id="updateSaleBtn" class="btn primary">Save</button>
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
'resources/js/sales/edit.js',
])
@endsection
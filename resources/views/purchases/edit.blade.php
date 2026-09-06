@extends('layouts.master')
@section('title', 'Edit Purchase')

@section('meta')
<meta name="purchaseId" content="{{ $purchase->id }}">
@endsection

@section('styles')
@vite([
'resources/css/purchases/create.css',
])
@endsection

@section('main')
<form id="form">
    <div class="main-header">
        <h1>Purchase #{{ $purchase->id }}</h1>
    </div>
    <div class="input-wrapper-card">
        <div class="input-wrapper">
            <label for="supplier_name">Supplier</label>
            <input type="text" name="supplier_name" id="supplierName" value="{{ $purchase->supplier_name }}" required>
            <span id="supplier_name_error"></span>
        </div>
        <div class="input-wrapper">
            <label for="purchase_date">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchaseDate" value="{{ $purchase->purchase_date }}" required>
            <span id="purchase_date_error"></span>
        </div>
    </div>
    <div class="purchase_items_card">
        <div class="card_header">
            <h2>Purchase Items</h2>
            <button type="button" id="addPurchaseItemBtn" class="btn-fit secondary"><i class="fa-solid fa-plus"></i>Add Item</button>
        </div>
        <div class="purchase-item-wrapper" id="purchaseItems">
            @php
            $purchaseItemsCount = 0;
            @endphp

            @foreach($purchase->purchaseItems as $purchaseItem)
            <div class="purchase-item-row" data-count="{{ $purchaseItemsCount }}">
                <!-- Product -->
                <div class="input-wrapper product-name">

                    <label for="purchase_items[{{ $purchaseItemsCount }}]product_id">Product</label>
                    <input
                        type="text"
                        name="purchase_items[{{ $purchaseItemsCount }}]product_id"
                        value="{{ $purchaseItem->product->name }}"
                        list="productList"
                        autocomplete="off"
                        required="required"
                        data-id="{{ $purchaseItem->product->id }}"
                        data-product-id="">

                    <span id="purchase_items[{{ $purchaseItemsCount }}]product_id_error"></span>

                </div>


                <!-- Quantity -->
                <div class="input-wrapper">
                    <label for="purchase_items[{{ $purchaseItemsCount }}]qty">Qty</label>
                    <div class="flex ai-end gap-thin">
                        <input
                            type="number"
                            name="purchase_items[{{ $purchaseItemsCount }}]qty"
                            value="{{ $purchaseItem->qty }}"
                            required="required"
                            data-qty="">

                        <span class="unit" data-unit>
                            {{ $purchaseItem->unit }}
                        </span>
                    </div>
                    <span id="purchase_items[{{ $purchaseItemsCount }}]qty_error"></span>
                </div>
                <!-- Hidden Unit -->
                <input
                    type="hidden"
                    value=""
                    name="purchase_items[{{ $purchaseItemsCount }}]unit"
                    data-unit="">

                <!-- Price -->
                <div class="input-wrapper">
                    <label for="purchase_items[{{ $purchaseItemsCount }}]price">Price</label>
                    <input
                        type="number"
                        name="purchase_items[{{ $purchaseItemsCount }}]price"
                        value="{{ $purchaseItem->price }}"
                        required="required"
                        data-price="">

                    <span id="purchase_items[{{ $purchaseItemsCount }}]price_error"></span>
                </div>


                <!-- Subtotal -->
                <div class="input-wrapper">
                    <label for="purchase_items[{{ $purchaseItemsCount }}]subtotal">Subtotal</label>
                    <input
                        type="number"
                        class="subtotal"
                        name="purchase_items[{{ $purchaseItemsCount }}]subtotal"
                        value="{{ $purchaseItem->subtotal }}"
                        required="required"
                        readonly="true"
                        data-subtotal="">
                </div>

                <!-- Remove Button -->
                <div class="x-icon-wrapper">
                    <button type="button" class="btn-icon x-icon" data-remove-btn>
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            @php
            $purchaseItemsCount++;
            @endphp
            @endforeach
            <input type="hidden" name="purchaseItemsCount" id="purchaseItemsCount" value="{{ $purchaseItemsCount }}">
        </div>
        <div class="total-amount-wrapper">
            <span class="total-amount-label">Total Amount: <strong id="totalAmount">₹{{ $purchase->total_amount }}</strong></span>
        </div>
    </div>
    <div class="flex jc-end">
        <button type="button" id="deletePurchaseBtn" class="btn error">Delete</button>
        <button type="button" id="updatePurchaseBtn" class="btn primary">Save</button>
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
'resources/js/purchases/edit.js',
])
@endsection
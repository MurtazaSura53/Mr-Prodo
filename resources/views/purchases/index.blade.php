@extends('layouts.master')
@section('title', 'Purchases')

@section('meta')
<meta name="lastPage" content="{{ $purchases->lastPage() }}">
@endsection

@section('styles')
@vite([
'resources/css/purchases/index.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Purchases</h1>
    <div class="btn-wrapper">
        <button type="button" class="btn-sml primary" id="addPurchaseBtn"><i class="fa-solid fa-plus"></i>Add</button>
    </div>
</div>
<div class="page-overview-strip">
    <h3>Total Purchases: <span id="totalItems">{{ $purchases->total() }}</span></h3>
    <span>Showing: <span id="showingPurchases">
            {{ $purchases->firstItem() }} - {{ $purchases->lastItem() }}
        </span></span>
</div>
<div class="purchase-grid" id="cardContainer">
    @foreach($purchases as $purchase)
    <div class="purchase-card">

        <div class="purchase-card-header">
            <span class="purchase-id">
                Purchase #{{ $purchase->id }}
            </span>
            <span class="purchase-total">
                ₹{{ $purchase->total_amount}}
            </span>
        </div>

        <div class="purchase-card-body">
            <div class="purchase-info">
                <span class="purchase-label">Supplier</span>
                <span class="purchase-value">{{ $purchase->supplier_name }}</span>
            </div>
            <div class="purchase-info">
                <span class="purchase-label">Purchase Date</span>
                <span class="purchase-value">{{ $purchase->purchase_date }}</span>
            </div>
        </div>

        <div class="purchase-card-footer">
            <a href="/purchases/edit/{{ $purchase->id}}" class="purchase-action">
                View Purchase
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>

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
'resources/js/purchases/index.js',
])
@endsection
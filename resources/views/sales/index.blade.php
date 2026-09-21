@extends('layouts.master')
@section('title', 'Sales')

@section('meta')
<meta name="lastPage" content="{{ $sales->lastPage() }}">
@endsection

@section('styles')
@vite([
'resources/css/sales/index.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Sales</h1>
    <div class="btn-wrapper">
        <button type="button" class="btn-sml primary" id="addSalesBtn"><i class="fa-solid fa-plus"></i>Add</button>
    </div>
</div>
<div class="page-overview-strip">
    <h3>Total Sales: <span id="totalItems">{{ $sales->total() }}</span></h3>
    <span>Showing: <span id="showingSales">
            {{ $sales->firstItem() }} - {{ $sales->lastItem() }}
        </span></span>
</div>

<div class="sale-grid" id="cardContainer">
    @foreach($sales as $sale)
    <div class="sale-card">
        <!-- Header -->
        <div class="sale-card-header">
            <span class="sale-card-id">#{{ $sale->id }}</span>
        </div>
        <!-- Customer -->
        @if($sale->customer !== null)
        <div class="sale-customer">
            <div class="sale-customer-name">
                {{ $sale->customer->name }}
            </div>
            <div class="sale-customer-detail">
                <i class="fa-solid fa-envelope"></i>
                {{ $sale->customer->email }}
            </div>
            <div class="sale-customer-detail">
                <i class="fa-solid fa-phone"></i>
                {{ $sale->customer->phone }}
            </div>
        </div>
        @else
        <div class="sale-customer">
            <div class="sale-customer-name sale-walk-in">
                Walk-in Customer
            </div>
            <div class="sale-customer-detail">
                <i class="fa-solid fa-envelope"></i>---
            </div>
            <div class="sale-customer-detail">
                <i class="fa-solid fa-phone"></i>---
            </div>
        </div>
        @endif

        <!-- Sale Date -->
        <div class="sale-info">
            <span class="sale-info-label">
                Sale Date
            </span>
            <span class="sale-info-value">
                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d F Y') }}
            </span>
        </div>

        <!-- Amount / Profit -->
        <div class="sale-summary">
            <div class="sale-summary-item">
                <span class="sale-summary-label">
                    Total Amount
                </span>
                <span class="sale-summary-value">
                    ₹{{ $sale->total_amount }}
                </span>
            </div>

            <div class="sale-summary-item">
                <span class="sale-summary-label">
                    Total Profit
                </span>
                <span class="sale-summary-value sale-profit">
                    ₹{{ $sale->total_profit }}
                </span>
            </div>
        </div>

        <!-- Action -->
        <div class="flex jc-end">
            <a href="/sales/edit/{{ $sale->id }}" class="text-decoration-none">
                <button type="button" class="btn primary">
                    View Sale
                </button>
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
'resources/js/sales/index.js',
])
@endsection
@extends('layouts.master')
@section('title', 'Dashboard')

@section('styles')
@vite([
'resources/css/dashboard/index.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Dashboard</h1>
    <div class="btn-wrapper">
    </div>
</div>
<div class="overview" id="overview">
    @foreach($dashboardData['overviews'] as $overview)
    <a href="{{ $overview['link'] }}">
        <div class="overview-card">
            <div class="circle"></div>
            <div class="card-header">
                <span>{{ $overview['label'] }}</span>
                <i class="{{ $overview['fa_icon'] }}"></i>
            </div>
            <div class="card-content">
                <span>{{ $overview['content'] }}</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
<div class="insights">
    <section class="recent-sales-section">
        <div class="insight-section-heading">
            <span>Recent Sales</span>
            <div class="heading-rhs">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div class="recent-sales">
            @foreach($dashboardData['recentSales'] as $recentSale)
            <a href="/sales/edit/{{$recentSale->id}}">
                <div class="recent-sale" data-recent-sale>
                    <span class="sale-id" data-sale-id="{{ $recentSale->id }}">#{{ $recentSale->id }}</span>
                    <span class="customer-name" data-customer>{{$recentSale->customer?->name ?? 'Walk in Customer'}}</span>
                    <span class="sale-amount" data-sale-amount>₹{{ number_format($recentSale->total_amount) }}</span>
                </div>
            </a>
            @endforeach

        </div>
        <div class="flex jc-center flex-end">
            <a href="{{route('sales')}}" class="text-decoration-none">
                <button type="button" class="primary btn-fluid">
                    View all
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </a>
        </div>
    </section>
    <section class="inventory-section">
        <div class="insight-section-heading">
            <span>Inventory</span>
            <div class="heading-rhs">
                <i class="fa-solid fa-warehouse"></i>
            </div>
        </div>

        <div class="inventory">
            <a href="{{route('products')}}" class="text-decoration-none">
                <div class="total-products inventory-row">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span class="flex-grow">Total Products</span>
                    <span class="inventory-count">{{$dashboardData['inventory']['totalProducts']}}</span>
                </div>
            </a>

            <div class="in-stock-products inventory-row">
                <i class="fa-solid fa-box"></i>
                <span class="flex-grow">In Stock</span>
                <span class="inventory-count">{{$dashboardData['inventory']['inStock']}}</span>
            </div>
            <div class="out-of-stock-products inventory-row">
                <i class="fa-solid fa-box-open"></i>
                <span class="flex-grow">Out Of Stock</span>
                <span class="inventory-count">{{$dashboardData['inventory']['outOfStock']}}</span>
            </div>
        </div>
    </section>
    <section class="top-seller-section">
        <div class="insight-section-heading">
            <span>Top Sellers</span>
            <div class="heading-rhs">
                <i class="fa-solid fa-trophy"></i>
            </div>
        </div>
        <div class="top-seller ">
            @foreach($dashboardData['topSellers'] as $product)
            <a href="/products/edit/{{$product->id}}" class="text-decoration-none top-seller-row {{ ($loop->iteration <= 3) ? 'rank-row-'.$loop->iteration : '' }}">
                <span class="product-id">
                    #{{$product->id}}
                    @if($loop->iteration <= 3)
                        <span class="rank rank-{{ $loop->iteration }} show-in-mobile hide-in-pc">
                        {{ $loop->iteration }}
                </span>
                @endif
                </span>
                <span class="flex-grow flex ai-center">
                    {{$product->name}}
                    @if($loop->iteration <= 3)
                        <span class=" hide-in-mobile show-in-pc rank rank-{{ $loop->iteration }}">
                        {{ $loop->iteration }}
                </span>
                @endif
                </span>
                <span class="product-total-sold">{{round($product->total_sold ?? 0) . " " . $product->unit}}</span>
            </a>
            @endforeach
        </div>
    </section>
</div>
@endsection

@section('scripts')
@vite([
'resources/js/dashboard/index.js',
])
@endsection
@extends('layouts.master')

@section('meta')
<meta name="lastPage" content="{{ $customers->lastPage() }}">
@endsection

@section('title', 'Customers')

@section('styles')
@vite([
'resources/css/customers/index.css',
])
@endsection

@section('main')
<div class="main-header">
    <h1>Customers</h1>
    <div class="btn-wrapper">
    </div>
</div>

<div class="page-overview-strip">
    <h3>Total Sales: <span id="totalItems">{{ $customers->total() }}</span></h3>
    <span>Showing: <span id="showingCustomers">
            {{ $customers->firstItem() }} - {{ $customers->lastItem() }}
        </span></span>
</div>

<div class="customer-grid" id="cardContainer">

    @foreach($customers as $customer)
    <div class="customer-card" data-customer-card>
        <!-- Header -->
        <div class="customer-card-header">
            <span class="customer-card-id" data-id="{{ $customer->id }}">
                #{{$customer->id}}
            </span>
        </div>
        <!-- Customer -->
        <div class="customer-info">
            <div class="customer-name" data-name="{{$customer->name}}">{{$customer->name}}</div>
            <div class="customer-detail" data-email="{{$customer->email}}">
                <i class="fa-solid fa-envelope"></i>{{$customer->email}}
            </div>
            <div class="customer-detail" data-phone="{{$customer->phone}}">
                <i class="fa-solid fa-phone"></i>{{$customer->phone ?? '---'}}
            </div>
        </div>
        <!-- Sales Summary -->
        <div class="customer-summary">
            <div class="customer-summary-item">
                <span class="customer-summary-label">Total Sales</span>
                <span class="customer-summary-value"
                    data-total-sale-amount="{{$customer->sales_sum_total_amount}}">
                    ₹{{$customer->sales_sum_total_amount}}
                </span>
            </div>
            <div class="customer-summary-item">
                <span class="customer-summary-label">Sales Made</span>
                <span class="customer-summary-value"
                    data-sale-count="{{$customer->sales_count}}">
                    {{$customer->sales_count}}
                </span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex jc-end">
            <button type="button" class="btn-fluid primary"
                data-edit>
                <i class="fa-solid fa-pen"></i>Edit
            </button>
            <button type="button" class="btn-fluid error"
                data-delete
                data-id="{{ $customer->id }}">
                <i class="fa-solid fa-trash"></i>Delete
            </button>
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
'resources/js/customers/index.js',
])
@endsection
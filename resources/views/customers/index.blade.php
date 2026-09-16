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
@endsection

@section('scripts')
@vite([
'resources/js/customers/index.js',
])
@endsection
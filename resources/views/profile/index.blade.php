@extends('layouts.master')
@section('title', 'Profile')

@section('styles')
@vite([
'resources/css/profile/index.css'
])
@endsection

@section('main')
<div class="main-header">
    <h1>Profile</h1>
</div>
<div class="form-wrapper">
    <form id="form" class="card">
        @csrf
        @method('PATCH')
        <div class="input-wrapper">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{ $user->name }}">
            <span id="name_error">
            </span>
        </div>
        <div class="input-wrapper">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ $user->email }}">
            <span id="email_error">
            </span>
        </div>
        <div class="submit-wrapper">
            <button id="submitBtn" type="button" class="primary btn"><i class="fa-solid fa-pencil"></i>Save</button>
        </div>
    </form>
</div>
@section('scripts')
@vite([
'resources/js/profile/index.js',
])
@endsection
@endsection
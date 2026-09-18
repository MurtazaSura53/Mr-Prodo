@extends('layouts.auth')

@section('title', 'Signup')

@section('styles')
@vite('resources/css/auth/signup.css')
@endsection


@section('eyebrow-header')
<span>Signup</span>
<h1>Start your journey now!</h1>
@endsection

@section('form')
<form id="form" class="card signup-form">
    @csrf
    <div class="input-wrapper">
        <label for="name">Username:</label>
        <input type="text" name="name" required>
        <span id="name_error"></span>
    </div>
    <div class="input-wrapper">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <span id="email_error"></span>
    </div>
    <div class="input-wrapper">
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <span id="password_error"></span>
    </div>
    <div class="flex center">
        <button type="button" id="storeSignupBtn" class="primary btn">Submit</button>
    </div>
</form>
@endsection

@section('scripts')
@vite('resources/js/auth/signup.js')
@endsection
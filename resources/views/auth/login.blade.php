@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
@vite('resources/css/auth/login.css')
@endsection


@section('eyebrow-header')
<span>Login</span>
<h1>Let’s build something great.</h1>
@endsection

@section('form')
<form id="form" action="{{ route('login.store') }}" method="POST" class="card signup-form">
    @csrf
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
        <button type="submit" class="primary btn">Submit</button>
    </div>
</form>
@endsection

@section('scripts')
@vite('resources/js/auth/login.js')
@endsection
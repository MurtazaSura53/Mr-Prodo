<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>@yield('title')</title>

    @include('partials/global')
    @yield('styles')
</head>

<body>
    <div class="container">
        @include('partials.header')
        @include('partials.sidenav')
        <main>
            @yield('main')
        </main>
    </div>

    <div id="alert-container"></div>
    <div id="toast-container"></div>
    <div id="prompt-container"></div>
    @yield('scripts')
</body>

</html>
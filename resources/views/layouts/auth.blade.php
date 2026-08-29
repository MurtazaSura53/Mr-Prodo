<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/style.css')
    @yield('styles')
</head>

<body>
    <div class="container">
        <header>
            <div class="logo">Mr.Prodo</div>
        </header>
        <main>
            <section class="s1">
                <div class="eyebrow-header">
                    @yield('eyebrow-header')
                </div>
            </section>
            <section class="s2 center">
                @yield('form')
            </section>
        </main>
    </div>
    @yield('scripts')
</body>

</html>
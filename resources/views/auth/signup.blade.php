<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    @vite('resources/css/style.css')
    @vite('resources/css/auth/signup.css')
</head>

<body>
    <div class="container">
        <header>
            <div class="logo">Mr.Prodo</div>
        </header>
        <main>
            <section class="s1">
                <div class="eyebrow-header">
                    <span>Signup</span>
                    <h1>Start your journey now!</h1>
                </div>
            </section>
            <section class="s2 center">
                <form id="form" action="{{ route('signup.store') }}" method="POST" class="card signup-form">
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
                        <button type="submit" class="primary btn">Submit</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
    @vite('resources/js/auth/signup.js');
</body>

</html>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Threecommerce') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('head-scripts')
</head>
<body style="background-color: #121212; color: #ffffff; font-family: Nunito, sans-serif;">
<div id="app">
    <header style="background: #1a1a1a; padding: 20px;">
        <div class="container d-flex justify-content-between align-items-center">
            <div style="display: flex; align-items: center;">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="height: 40px; margin-right: 10px;">
            </div>
            @auth
            <nav>
                <ul style="list-style: none; display: flex; margin: 0; padding: 0;">
                    <li style="margin-right: 20px;">
                        <a href="{{ route('repo.index') }}" style="color: #ffffff; text-decoration: none;">Repo</a>
                    </li>
                    <li style="margin-right: 20px;">
                        <a href="{{ route('oauth_repo.index') }}" style="color: #ffffff; text-decoration: none;">OAuth Repo</a>
                    </li>
                    <li style="margin-right: 20px;">
                        <a href="{{ route('oauth_user.index') }}" style="color: #ffffff; text-decoration: none;">OAuth User</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           style="color: #ffffff; text-decoration: none;">Logout</a>
                    </li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
            @endauth
        </div>
    </header>

    <main class="py-4" style="padding: 20px;">
        @yield('content')
    </main>

    <footer style="background: #1a1a1a; padding: 10px; text-align: center; margin-top: 20px; color: #888;">
        <p>© {{ date('Y') }} ThreeCommerce. All Rights Reserved.</p>
    </footer>
</div>

<!-- Dynamic Scripts -->
@stack('scripts')
</body>
</html>
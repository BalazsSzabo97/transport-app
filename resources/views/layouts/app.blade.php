<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Fuvarozó App' }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Fuvarozó App</a>

            @auth('admin')
                <span class="text-white me-3">Bejelentkezve Adminisztrátorként</span>
                <a class="btn btn-outline-light" href="{{ route('logout') }}">Kijelentkezés</a>

            @endauth

            @auth('driver')
                <span class="text-white me-3">Bejelentkezve Fuvarozóként</span>
                <a class="btn btn-outline-light" href="{{ route('logout') }}">Kijelentkezés</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

</body>

</html>
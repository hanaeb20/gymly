<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GymLy') }}</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Antonio:wght@400;700&family=Anybody:wght@400;700&display=swap" rel="stylesheet">

    <!-- Styles poussés -->
    @stack('styles')

    <style>
        body {
            font-family: 'Antic', sans-serif;
        }

        .navbar {
            background-color: #DE2A80;
            padding: 15px 0;
        }

        .navbar-brand img {
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .nav-link {
            color: white !important;
            font-size: 16px;
            margin: 0 10px;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
            z-index: 9999;
        }

        .nav-link:hover {
            color: #2B2051 !important;
            transform: translateY(-2px);
        }

        .auth-button {
            background-color: transparent;
            border: 2px solid white;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            margin: 0 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .auth-button:hover {
            background-color: white;
            color: #DE2A80;
            transform: translateY(-2px);
        }

        .auth-button.register {
            background-color: #2B2051;
            border-color: #2B2051;
        }

        .auth-button.register:hover {
            background-color: #1a1436;
            border-color: #1a1436;
            color: white;
        }

        .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #DE2A80;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="GymLy">
                </a>

                <!-- Bouton hamburger -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu principal -->
                <div class="collapse navbar-collapse" id="navbarMain">
                    <!-- Liens de gauche -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/salles') }}">Salles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/cours') }}">Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/coachs') }}">Coachs</a>
                        </li>

                    </ul>

                    <!-- Liens de droite -->
                    <div class="navbar-nav ms-auto">
                        @guest
                            <a href="{{ route('login') }}" class="auth-button">Se connecter</a>
                            <a href="{{ route('register') }}" class="auth-button register">S'inscrire</a>
                        @else
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(Auth::user()->role === 'gerant')
                                        <li><a class="dropdown-item" href="{{ route('gerant.dashboard') }}">Espace Gérant</a></li>
                                        <li><a class="dropdown-item" href="{{ route('gerant.horaires.index') }}">Gestion Horaires</a></li>
                                    @elseif(Auth::user()->role === 'coach')
                                        <li><a class="dropdown-item" href="{{ route('essaie') }}">Espace Coach</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ url('/profile') }}">Mon Profil</a></li>
                                        <li><a class="dropdown-item" href="{{ url('/reservations') }}">Mes Réservations</a></li>
                                        <li><a class="dropdown-item" href="{{ route('declarations.create') }}">Mes Déclarations</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Se déconnecter</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

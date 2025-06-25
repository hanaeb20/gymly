@extends('layouts.app')

@section('content')
<div class="main-container">
    <nav class="top-nav">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </div>
        <div class="nav-links">
            <a href="#" class="nav-link">Salle de sport</a>
            <a href="#" class="nav-link">Cours</a>
            <a href="#" class="nav-link">Mon profile</a>
            <a href="#" class="nav-link">Contact</a>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="image-section">
            <img src="{{ asset('images/login-image.jpg') }}" alt="Reset Password" class="login-image">
        </div>

        <div class="form-section">
            <form method="POST" action="{{ route('password.email') }}" class="login-form">
                @csrf
                <h1 class="form-title">Réinitialisation</h1>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                    <div class="form-line"></div>
                </div>

                <button type="submit" class="btn-login">Envoyer le lien</button>

                <div class="back-to-login">
                    <a href="{{ route('login') }}">Retour à la connexion</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Anybody&family=Anuphan&display=swap');

    /* Réutilisation des styles de login.blade.php */
    .main-container {
        width: 1600px;
        height: 900px;
        position: relative;
        margin: 0 auto;
        background: linear-gradient(90deg, #DE2A80 0%, rgba(245.44, 34.16, 135.10, 0.60) 50%, #460725 100%);
        overflow: hidden;
    }

    /* ... Copier tous les autres styles de login.blade.php ... */

    .alert {
        width: 100%;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        text-align: center;
    }

    .alert-success {
        background: rgba(163, 151, 203, 0.2);
        color: white;
        border: 1px solid #A397CB;
    }

    .back-to-login {
        margin-top: 20px;
    }

    .back-to-login a {
        color: #2B2051;
        font-size: 24px;
        font-family: 'Anuphan', sans-serif;
        font-weight: 400;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .back-to-login a:hover {
        color: #E54892;
    }
</style>
@endsection

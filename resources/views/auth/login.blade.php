@extends('layouts.app')

@section('content')
<div class="main-wrapper">
    <div class="content-container">
        <div class="background-gradient"></div>

        <div class="form-container">
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <h1 class="form-title">connexion</h1>

                <div class="form-content">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" value="{{ old('password') }}" required autofocus>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <button type="button" class="show-password-button" value="connecter">
                    </div>

                    <div class="form-group remember-me">
                        <label for="remember">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="login-button">
                        <span class="button-text">Se connecter</span>
                        <span class="button-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                    </button>

                    <a href="{{ route('password.request') }}" class="forgot-password">mot de passe oublie ?</a>
                </div>
            </form>
        </div>

        <img class="main-image" src="{{ asset('images/connexion.jpg') }}" alt="Connexion" />
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Anybody&family=Anuphan&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: #1a1a1a;
    }

    .main-wrapper {
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .content-container {
        width: 100%;
        height: 100%;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .background-gradient {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        background: linear-gradient(90deg,
            #DE2A80 0%,
            rgba(245.44, 34.16, 135.10, 0.60) 40%,
            rgba(245.44, 34.16, 135.10, 0.40) 55%,
            #460725 85%,
            #2B0413 100%
        );
        animation: gradientAnimation 15s ease infinite;
        background-size: 200% 200%;
    }

    @keyframes gradientAnimation {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    .logo {
        width: 135px;
        height: 135px;
        position: absolute;
        left: 69px;
        top: 27px;
        border-radius: 220.50px;
        z-index: 1;
        box-shadow: 0 0 20px rgba(222, 42, 128, 0.3);
        transition: transform 0.3s ease;
    }

    .logo:hover {
        transform: scale(1.05);
    }

    .form-container {
        width: 800px;
        height: 627px;
        position: absolute;
        right: 54px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 55px;
        backdrop-filter: blur(20px);
        z-index: 1;
        transition: all 0.4s ease;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.3),
            inset 0 0 80px rgba(70, 7, 37, 0.2);
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at center,
            rgba(70, 7, 37, 0.1) 0%,
            rgba(70, 7, 37, 0.05) 30%,
            transparent 70%);
        animation: rotateGradient 15s linear infinite;
        pointer-events: none;
    }

    .form-container::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg,
            rgba(229, 72, 146, 0.1) 0%,
            rgba(222, 42, 128, 0.05) 100%);
        border-radius: 55px;
        pointer-events: none;
    }

    @keyframes rotateGradient {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .form-container:hover {
        transform: translateY(-51%);
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.3),
            inset 0 0 80px rgba(229, 72, 146, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .login-form {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px;
    }

    .form-title {
        color: #E54892;
        font-size: 48px;
        font-family: 'Anton', sans-serif;
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 60px;
        background: linear-gradient(45deg, #E54892, #DE2A80);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: titlePulse 2s ease-in-out infinite;
    }

    @keyframes titlePulse {
        0%, 100% {
            opacity: 1;
            transform: translateX(-50%) scale(1);
            text-shadow: 0 0 20px rgba(229, 72, 146, 0.5);
        }
        50% {
            opacity: 0.8;
            transform: translateX(-50%) scale(1.02);
            text-shadow: 0 0 30px rgba(229, 72, 146, 0.8);
        }
    }

    .form-content {
        width: 100%;
        max-width: 430px;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .form-group {
        width: 100%;
    }

    .form-group label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 24px;
        font-family: 'Anuphan', sans-serif;
        font-weight: 400;
        margin-bottom: 15px;
        display: block;
        position: relative;
        padding-left: 35px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .form-group label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        background-size: contain;
        background-repeat: no-repeat;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .form-group:nth-child(1) label::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff' viewBox='0 0 24 24'%3E%3Cpath d='M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z'/%3E%3C/svg%3E");
    }

    .form-group:nth-child(2) label::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff' viewBox='0 0 24 24'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z'/%3E%3C/svg%3E");
    }

    .form-group input {
        width: 100%;
        height: 50px;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.07);
        color: white;
        font-size: 20px;
        font-family: 'Anuphan', sans-serif;
        padding: 0 20px 0 45px;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1),
                    0 2px 4px rgba(255, 255, 255, 0.05);
    }

    .form-group input:focus {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(229, 72, 146, 0.5);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1),
                    0 0 15px rgba(229, 72, 146, 0.3);
        transform: translateY(-2px);
    }

    .login-button {
        width: 100%;
        height: 60px;
        background: linear-gradient(45deg, #E54892, #DE2A80);
        border-radius: 30px;
        border: none;
        color: white;
        font-size: 24px;
        font-family: 'Anybody', sans-serif;
        font-weight: 500;
        cursor: pointer;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(229, 72, 146, 0.3);
        overflow: hidden;
        position: relative;
    }

    .login-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .login-button:hover::before {
        left: 100%;
    }

    .login-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(229, 72, 146, 0.4);
        background: linear-gradient(45deg, #DE2A80, #E54892);
    }

    .login-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(229, 72, 146, 0.2);
    }

    .button-icon {
        font-size: 24px;
        transition: transform 0.3s ease;
    }

    .login-button:hover .button-icon {
        transform: translateX(5px);
    }

    .forgot-password {
        color: rgba(255, 255, 255, 0.7);
        font-size: 18px;
        font-family: 'Anuphan', sans-serif;
        font-weight: 400;
        text-decoration: none;
        transition: all 0.3s ease;
        text-align: center;
        display: block;
        margin-top: 20px;
    }

    .forgot-password:hover {
        color: #E54892;
        text-shadow: 0 0 10px rgba(229, 72, 146, 0.6);
    }

    .main-image {
        width: 620px;
        height: 620px;
        position: absolute;
        left: 159px;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 156px;
        object-fit: cover;
        z-index: 1;
        transition: all 0.5s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        filter: brightness(1.1) contrast(1.1);
    }

    .main-image:hover {
        transform: translateY(-51%) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        filter: brightness(1.2) contrast(1.15);
    }

    input:focus {
        outline: none;
    }

    @media screen and (max-width: 1600px) {
        .content-container {
            transform: scale(0.8);
            transform-origin: center;
        }
    }
</style>
@endsection

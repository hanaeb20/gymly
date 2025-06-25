@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="form-section">

        <div class="registration-form">
            <h1 class="form-title">S'inscrire</h1>

            <div class="profile-tabs">
                <a href="{{ route('register', ['type' => 'client']) }}" class="profile-tab active">
                    <i class="fas fa-user tab-icon"></i>
                    <span>UTILISATEUR</span>
                </a>
                <a href="{{ route('register.coach') }}" class="profile-tab">
                    <i class="fas fa-dumbbell tab-icon"></i>
                    <span>COACH</span>
                </a>
                <a href="{{ route('register.gerant') }}" class="profile-tab">
                    <i class="fas fa-building tab-icon"></i>
                    <span>GERANT</span>
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}" class="animated-form">
                @csrf
                <input type="hidden" name="type" value="client">

                <div class="form-floating">
                    <input type="text" class="form-control" name="nom" placeholder="Votre nom" required>
                    <div class="form-icon"><i class="fas fa-user"></i></div>
                    <div class="form-line"></div>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" name="prenom" placeholder="Votre prénom" required>
                    <div class="form-icon"><i class="fas fa-user"></i></div>
                    <div class="form-line"></div>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" name="email" placeholder="Votre adresse email" required>
                    <div class="form-icon"><i class="fas fa-envelope"></i></div>
                    <div class="form-line"></div>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" name="password" placeholder="Créez votre mot de passe" required>
                    <div class="form-icon"><i class="fas fa-lock"></i></div>
                    <div class="form-line"></div>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
                    <div class="form-icon"><i class="fas fa-lock"></i></div>
                    <div class="form-line"></div>
                </div>

                <button type="submit" class="btn-register">
                    <span>S'inscrire</span>
                    <div class="button-effect"></div>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="image-section">
        <div class="overlay"></div>
        <div class="image-overlay-effect"></div>
    </div>
</div>

    <style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #DE2A80;
        --secondary-color: #1E1E1E;
        --accent-color: #A397CB;
        --gradient-start: #DE2A80;
        --gradient-end: #580D31;
        --text-color: #FFFFFF;
    }

    .main-container {
        display: flex;
        min-height: calc(100vh - 76px);
        max-width: 1400px;
        margin: 2rem auto;
            padding: 0;
        position: relative;
        overflow: hidden;
            font-family: 'Poppins', sans-serif;
        box-shadow: 0 15px 40px rgba(222, 42, 128, 0.2);
        border-radius: 30px;
        height: 800px;
        background: var(--secondary-color);
    }

    .form-section {
        flex: 0.55;
        background: var(--secondary-color);
        background-image:
            radial-gradient(circle at 20% 20%, rgba(222, 42, 128, 0.4) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(163, 151, 203, 0.4) 0%, transparent 50%);
        padding: 3rem 2rem;
        border-radius: 30px 0 0 30px;
        color: var(--text-color);
        position: relative;
        z-index: 1;
    }

    .image-section {
        flex: 0.45;
            position: relative;
            overflow: hidden;
        border-radius: 0 30px 30px 0;
        border-left: 4px solid var(--primary-color);
        }

    .image-section::before {
        content: '';
            position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/images/utilisateur.png') center/cover no-repeat;
        background-size: cover;
        background-position: center;
        filter: grayscale(20%) contrast(110%);
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    .image-section:hover::before {
        transform: scale(1);
    }

    .image-section .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg,
            rgba(222, 42, 128, 0.6),
            rgba(88, 13, 49, 0.3)
        );
        animation: pulse 3s infinite;
    }

    .registration-form {
        max-width: 450px;
        margin: 0 auto;
            position: relative;
            z-index: 2;
        padding: 2rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-title {
        font-size: 2.5rem;
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
    }

    .form-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    .profile-tabs {
        display: flex;
        margin-bottom: 1.5rem;
        padding: 0.5rem;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
    }

    .profile-tab {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
        color: var(--text-color);
        text-decoration: none;
        margin: 0 0.3rem;
        transition: all 0.3s ease;
        border-radius: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-tab.active {
        background: var(--primary-color);
        box-shadow: 0 4px 15px rgba(222, 42, 128, 0.3);
    }

    .form-floating {
        position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 0.8rem 1rem 0.8rem 3rem;
        color: var(--text-color);
        font-size: 0.9rem;
            transition: all 0.3s ease;
        width: 100%;
        }

        .form-control:focus {
        border-color: var(--primary-color);
        background: rgba(222, 42, 128, 0.1);
        box-shadow: 0 0 0 3px rgba(222, 42, 128, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        font-style: italic;
        transition: all 0.3s ease;
    }

    .form-control:focus::placeholder {
        color: rgba(255, 255, 255, 0.4);
        transform: translateX(5px);
    }

    .form-floating:hover .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .form-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        transition: all 0.3s ease;
        }

        .btn-register {
        background: linear-gradient(45deg, var(--gradient-start), var(--gradient-end));
        color: var(--text-color);
            border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        width: 100%;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
            font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        position: relative;
        overflow: hidden;
    }

    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
            width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: 0.5s;
    }

    .btn-register:hover::before {
        left: 100%;
        }

        .btn-register:hover {
        background: linear-gradient(45deg, var(--gradient-end), var(--gradient-start));
            transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(222, 42, 128, 0.4);
    }

    .animated-background {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        overflow: hidden;
        z-index: 0;
    }

    .gradient-circle {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
    }

    .circle-1 {
        width: 300px;
        height: 300px;
        background: rgba(222, 42, 128, 0.3);
        top: -100px;
        left: -100px;
        animation: float 8s infinite;
    }

    .circle-2 {
        width: 400px;
        height: 400px;
        background: rgba(163, 151, 203, 0.3);
        bottom: -150px;
        right: -150px;
        animation: float 10s infinite reverse;
    }

    .circle-3 {
        width: 200px;
        height: 200px;
        background: rgba(88, 13, 49, 0.3);
        top: 50%;
        left: 50%;
        animation: float 12s infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translate(0, 0);
        }
        25% {
            transform: translate(10px, -10px);
        }
        50% {
            transform: translate(-5px, 5px);
        }
        75% {
            transform: translate(-10px, 10px);
        }
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 0.8; }
    }

    @media (max-width: 1200px) {
        .main-container {
            max-width: 95%;
            height: auto;
            margin: 1rem auto;
        }
        }

        @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            height: auto;
            margin: 0;
            border-radius: 0;
        }

        .form-section {
            border-radius: 0;
            padding: 2rem 1rem;
        }

        .image-section {
            min-height: 250px;
            border-radius: 0;
            border-left: none;
            border-top: 4px solid var(--primary-color);
            }
        }
    </style>
@endsection

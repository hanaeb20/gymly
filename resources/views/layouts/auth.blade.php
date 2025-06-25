<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GymLy') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Antic&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .auth-container {
            width: 100%;
            min-height: 100vh;
            position: relative;
        }

        .pink-bg {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #DE2A80;
            z-index: -2;
        }

        .content-area {
            position: relative;
            z-index: 1;
            padding-top: 160px;
            padding-left: 100px;
        }

        .main-title {
            color: white;
            font-size: 72px;
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            margin: 0;
        }

        .sub-title {
            color: white;
            font-size: 28px;
            font-family: 'Antic', sans-serif;
            font-weight: 400;
            max-width: 600px;
            margin-top: 32px;
        }

        .purple-wave {
            position: absolute;
            left: 0;
            top: -90px;
            z-index: 0;
            width: 100%;
            height: 860px;
        }

        .boxer-image {
            width: 1100px;
            height: auto;
            position: absolute;
            right: 0;
            top: 50px;
            z-index: -1;
        }

        .auth-form-container {
            position: relative;
            z-index: 2;
            margin-top: 50px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .auth-card-header {
            background: #2B2051;
            color: white;
            padding: 20px;
            text-align: center;
            font-family: 'Anton', sans-serif;
        }

        .auth-card-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #DE2A80;
            box-shadow: 0 0 0 0.2rem rgba(222, 42, 128, 0.25);
        }

        .btn-auth {
            background-color: #DE2A80;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-family: 'Antic', sans-serif;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            background-color: #2B2051;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 80px 20px;
            }

            .main-title {
                font-size: 48px;
            }

            .sub-title {
                font-size: 24px;
            }

            .boxer-image {
                width: 100%;
                opacity: 0.3;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Fond rose -->
        <div class="pink-bg"></div>

        <!-- Contenu principal -->
        <div class="content-area">
            <h1 class="main-title">GYMLY</h1>
            <p class="sub-title">Rejoignez la communauté et dépassez vos limites</p>
        </div>

        <!-- Vague violette -->
        <svg class="purple-wave" viewBox="0 0 1619 872" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 871C1289.93 863.629 1616.14 287.262 1618 0V871H0Z" fill="#2B2051"/>
        </svg>

        <!-- Image du boxeur -->
        <img class="boxer-image" src="{{ asset('images/box.png') }}" alt="Boxer">

        <!-- Contenu spécifique de la page -->
        <div class="auth-form-container">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir votre profil - GYMLY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #DE2A80;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            background: white;
            transform: rotate(15deg);
        }

        .wave-left {
            width: 400px;
            height: 1200px;
            left: -200px;
            top: -400px;
            opacity: 0.1;
        }

        .wave-right {
            width: 400px;
            height: 1200px;
            right: -200px;
            bottom: -400px;
            opacity: 0.1;
        }

        .main-container {
            padding-top: 80px;
            position: relative;
            z-index: 2;
        }

        .title {
            color: white;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 50px;
        }

        .cards-row {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-card {
            width: 300px;
            height: 180px;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            text-decoration: none;
        }

        .profile-card.client {
            background-color: #2B2051;
        }

        .profile-card.coach {
            background-color: rgba(222, 42, 128, 0.8);
        }

        .profile-card.gerant {
            background-color: #8B1B55;
        }

        .profile-card h2 {
            color: white;
            font-size: 28px;
            margin: 0;
        }

        .profile-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 25px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        @media (max-width: 992px) {
            .cards-row {
                flex-direction: column;
                align-items: center;
            }

            .profile-card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="wave wave-left"></div>
    <div class="wave wave-right"></div>

    <div class="main-container">
        <h1 class="title">Espace</h1>

        <div class="cards-row">
            <a href="{{ route('register', ['type' => 'client']) }}" class="profile-card client">
                <h2>Client</h2>
                <span class="profile-btn">Espace client</span>
            </a>

            <a href="{{ route('register', ['type' => 'coach']) }}" class="profile-card coach">
                <h2>Coach</h2>
                <span class="profile-btn">Espace coach</span>
            </a>

            <a href="{{ route('register', ['type' => 'gerant']) }}" class="profile-card gerant">
                <h2>Gerant</h2>
                <span class="profile-btn">Espace gérant</span>
            </a>
        </div>
    </div>
</body>
</html>

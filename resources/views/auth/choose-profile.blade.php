<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir votre profil - GYMLY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #DE2A80 0%, #FF1B6B 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            background: white;
            transform: rotate(15deg);
            transition: all 0.5s ease;
        }

        .wave-left {
            width: 400px;
            height: 1200px;
            left: -200px;
            top: -400px;
            opacity: 0.1;
            animation: waveLeft 15s infinite;
        }

        .wave-right {
            width: 400px;
            height: 1200px;
            right: -200px;
            bottom: -400px;
            opacity: 0.1;
            animation: waveRight 15s infinite;
        }

        @keyframes waveLeft {
            0%, 100% { transform: rotate(15deg) translateY(0); }
            50% { transform: rotate(15deg) translateY(-30px); }
        }

        @keyframes waveRight {
            0%, 100% { transform: rotate(15deg) translateY(0); }
            50% { transform: rotate(15deg) translateY(30px); }
        }

        .main-container {
            padding-top: 80px;
            position: relative;
            z-index: 2;
        }

        .title {
            color: white;
            text-align: center;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 60px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cards-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-card {
            width: 320px;
            height: 220px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 25px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            z-index: 1;
        }

        .profile-card.client {
            background: linear-gradient(135deg, #2B2051 0%, #3B3B7C 100%);
            animation-delay: 0.2s;
        }

        .profile-card.coach {
            background: linear-gradient(135deg, rgba(222, 42, 128, 0.9) 0%, rgba(255, 27, 107, 0.9) 100%);
            animation-delay: 0.4s;
        }

        .profile-card.gerant {
            background: linear-gradient(135deg, #8B1B55 0%, #A71B69 100%);
            animation-delay: 0.6s;
        }

        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .profile-card h2 {
            color: white;
            font-size: 32px;
            margin: 0;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }

        .profile-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .icon {
            font-size: 40px;
            color: white;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        @media (max-width: 992px) {
            .cards-row {
                flex-direction: column;
                align-items: center;
                gap: 30px;
            }

            .profile-card {
                width: 90%;
                max-width: 320px;
                height: 200px;
            }

            .title {
                font-size: 2.5rem;
                margin-bottom: 40px;
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
                <i class="fas fa-user icon"></i>
                <h2>Client</h2>
                <span class="profile-btn">Espace client</span>
            </a>

            <a href="{{ route('register', ['type' => 'coach']) }}" class="profile-card coach">
                <i class="fas fa-dumbbell icon"></i>
                <h2>Coach</h2>
                <span class="profile-btn">Espace coach</span>
            </a>

            <a href="{{ route('register.gerant') }}" class="profile-card gerant">
                <i class="fas fa-building icon"></i>
                <h2>Gerant</h2>
                <span class="profile-btn">Espace gérant</span>
            </a>
        </div>
    </div>
</body>
</html>

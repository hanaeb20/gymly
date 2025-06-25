<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message de contact</title>
</head>
<body>
    <h2>Nouveau message de contact</h2>

    <p>Vous avez reçu un nouveau message de contact de la part de {{ $contact->user->name }}.</p>

    <h3>Détails du message :</h3>
    <p><strong>Sujet :</strong> {{ $contact->sujet }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $contact->message }}</p>

    <p>Pour répondre à ce message, veuillez vous connecter à votre espace administrateur.</p>

    <p>Cordialement,<br>L'équipe Gymly</p>
</body>
</html>

\<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
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
                                        <li><a class="dropdown-item" href="{{ route('coach.essaie') }}">Espace Coach</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ url('/profile') }}">Mon Profil</a></li>
                                        <li><a class="dropdown-item" href="{{ url('/reservations') }}">Mes Réservations</a></li>
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
            <div class="container mt-4">
                <div class="row">
                    <!-- Section Gestion des Réservations -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">Gestion des Réservations</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Cours</th>
                                                <th>Date</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservations as $reservation)
                                            <tr>
                                                <td>{{ $reservation->client ? $reservation->client->name : 'Client inconnu' }}</td>
                                                <td>{{ $reservation->cours ? $reservation->cours->nom : 'Cours inconnu' }}</td>
                                                <td>{{ $reservation->date_reservation ? $reservation->date_reservation->format('d/m/Y') : 'Date inconnue' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $reservation->statut === 'confirmée' ? 'success' : 'warning' }}">
                                                        {{ $reservation->statut ?? 'En attente' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" onclick="gererReservation({{ $reservation->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="supprimerReservation({{ $reservation->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Gestion des Cours -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h3 class="mb-0">Gestion des Cours</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterCoursModal">
                                        <i class="fas fa-plus"></i> Ajouter un cours
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom du cours</th>
                                                <th>Coach</th>
                                                <th>Capacité</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cours as $cour)
                                            <tr>
                                                <td>{{ $cour->nom ?? 'Cours sans nom' }}</td>
                                                <td>{{ $cour->coach ? $cour->coach->name : 'Coach inconnu' }}</td>
                                                <td>{{ $cour->capacite ?? 0 }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" onclick="modifierCours({{ $cour->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="supprimerCours({{ $cour->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ajouter Cours -->
            <div class="modal fade" id="ajouterCoursModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter un nouveau cours</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formAjouterCours">
                                <div class="mb-3">
                                    <label class="form-label">Nom du cours</label>
                                    <input type="text" class="form-control" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date du cours</label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Capacité maximale</label>
                                    <input type="number" class="form-control" name="capacite" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type de cours</label>
                                    <select class="form-select" name="type_cours" required>
                                        <option value="yoga">Yoga</option>
                                        <option value="musculation">Musculation</option>
                                        <option value="cardio">Cardio</option>
                                        <option value="crossfit">Crossfit</option>
                                        <option value="pilates">Pilates</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Durée (minutes)</label>
                                    <input type="number" class="form-control" name="duree_minutes" min="30" max="180" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Niveau</label>
                                    <select class="form-select" name="niveau" required>
                                        <option value="debutant">Débutant</option>
                                        <option value="intermediaire">Intermédiaire</option>
                                        <option value="avance">Avancé</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Heure de début</label>
                                    <input type="time" class="form-control" name="heure_debut" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control" name="heure_fin" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="ajouterCours()">Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Modifier Cours -->
            <div class="modal fade" id="modifierCoursModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier le cours</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formModifierCours">
                                <input type="hidden" name="cours_id" id="cours_id">
                                <div class="mb-3">
                                    <label class="form-label">Nom du cours</label>
                                    <input type="text" class="form-control" name="nom" id="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date du cours</label>
                                    <input type="date" class="form-control" name="date" id="date" required>
                                </div>
                                <input type="hidden" name="coach_id" value="{{ Auth::id() }}">
                                <div class="mb-3">
                                    <label class="form-label">Capacité maximale</label>
                                    <input type="number" class="form-control" name="capacite" id="capacite" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type de cours</label>
                                    <select class="form-select" name="type_cours" id="type_cours" required>
                                        <option value="yoga">Yoga</option>
                                        <option value="musculation">Musculation</option>
                                        <option value="cardio">Cardio</option>
                                        <option value="crossfit">Crossfit</option>
                                        <option value="pilates">Pilates</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Durée (minutes)</label>
                                    <input type="number" class="form-control" name="duree_minutes" id="duree_minutes" min="30" max="180" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Niveau</label>
                                    <select class="form-select" name="niveau" id="niveau" required>
                                        <option value="debutant">Débutant</option>
                                        <option value="intermediaire">Intermédiaire</option>
                                        <option value="avance">Avancé</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Heure de début</label>
                                    <input type="time" class="form-control" name="heure_debut" id="heure_debut" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control" name="heure_fin" id="heure_fin" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="sauvegarderModification()">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Gérer Réservation -->
            <div class="modal fade" id="gererReservationModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Gérer la réservation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formGererReservation">
                                <input type="hidden" name="reservation_id" id="reservation_id">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut" id="statut" required>
                                        <option value="en_attente">En attente</option>
                                        <option value="confirmée">Confirmée</option>
                                        <option value="annulée">Annulée</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Commentaire</label>
                                    <textarea class="form-control" name="commentaire" id="commentaire" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="sauvegarderStatutReservation()">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        // Fonctions pour la gestion des réservations
        function gererReservation(id) {
            // Récupérer les données de la réservation
            fetch(`/reservations/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('reservation_id').value = data.id;
                document.getElementById('statut').value = data.statut;
                document.getElementById('commentaire').value = data.commentaire || '';

                const modal = new bootstrap.Modal(document.getElementById('gererReservationModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la récupération des données de la réservation');
            });
        }

        function supprimerReservation(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
                fetch(`/reservations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression de la réservation');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue');
                });
            }
        }

        // Fonctions pour la gestion des cours
        function modifierCours(id) {
            // Récupérer les données du cours
            fetch(`/cours/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data); // Log pour débogage

                // Remplir le formulaire avec les données
                document.getElementById('cours_id').value = data.id;
                document.getElementById('nom').value = data.nom;
                document.getElementById('date').value = data.date ? data.date.format('Y-m-d') : '';
                document.getElementById('capacite').value = data.capacite_max;
                document.getElementById('description').value = data.description || '';
                document.getElementById('type_cours').value = data.type_cours;
                document.getElementById('duree_minutes').value = data.duree_minutes;
                document.getElementById('niveau').value = data.niveau;

                // Formater les horaires
                const horaireDebut = new Date(data.horaire_debut);
                const horaireFin = new Date(data.horaire_fin);
                document.getElementById('heure_debut').value = horaireDebut.toTimeString().slice(0, 5);
                document.getElementById('heure_fin').value = horaireFin.toTimeString().slice(0, 5);

                // Afficher le modal
                const modal = new bootstrap.Modal(document.getElementById('modifierCoursModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                let errorMessage = 'Une erreur est survenue lors de la récupération des données du cours';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                alert(errorMessage);
            });
        }

        function sauvegarderModification() {
            const form = document.getElementById('formModifierCours');
            const formData = new FormData(form);
            const coursId = formData.get('cours_id');

            // Convertir FormData en objet JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Ajouter la salle_id
            data.salle_id = {{ Auth::user()->salle_id ?? 1 }};

            fetch(`/cours/${coursId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modifierCoursModal'));
                    modal.hide();
                    // Recharger la page
                    location.reload();
                } else {
                    alert('Erreur lors de la modification du cours: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                let errorMessage = 'Une erreur est survenue';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                alert(errorMessage);
            });
        }

        function supprimerCours(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
                fetch(`/cours/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression du cours');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue');
                });
            }
        }

        function ajouterCours() {
            const form = document.getElementById('formAjouterCours');
            const formData = new FormData(form);

            // Convertir FormData en objet JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Ajouter la salle_id et le coach_id
            data.salle_id = {{ Auth::user()->salle_id ?? 1 }};
            data.coach_id = {{ Auth::id() }};

            console.log('Données envoyées:', data); // Log pour débogage

            fetch('/cours', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Réponse reçue:', response); // Log pour débogage
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Erreur serveur:', err); // Log pour débogage
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data); // Log pour débogage
                if (data.success) {
                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('ajouterCoursModal'));
                    modal.hide();
                    // Recharger la page
                    location.reload();
                } else {
                    alert('Erreur lors de l\'ajout du cours: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error); // Log pour débogage
                let errorMessage = 'Une erreur est survenue';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                alert(errorMessage);
            });
        }

        function sauvegarderStatutReservation() {
            const form = document.getElementById('formGererReservation');
            const formData = new FormData(form);
            const reservationId = formData.get('reservation_id');

            const data = {
                statut: formData.get('statut'),
                commentaire: formData.get('commentaire')
            };

            fetch(`/reservations/${reservationId}/statut`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('gererReservationModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la mise à jour du statut');
            });
        }
    </script>
</body>
</html>

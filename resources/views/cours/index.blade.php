@extends('layouts.app')

@section('content')
<style>
    .card {
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background: linear-gradient(45deg, #DE2A80, #580D31);
    }

    .card-text i {
        width: 25px;
        color: #DE2A80;
    }

    .badge {
        font-size: 0.9em;
        padding: 8px 12px;
    }

    .progress {
        height: 10px;
        border-radius: 5px;
    }

    .progress-bar {
        background-color: #DE2A80;
    }

    .time-info {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .time-info i {
        margin-right: 8px;
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Nos Cours</h1>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('cours.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <select name="type_cours" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach($types_cours as $type)
                                    <option value="{{ $type }}" {{ request('type_cours') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="jour" class="form-select">
                                <option value="">Tous les jours</option>
                                @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                    <option value="{{ $jour }}" {{ request('jour') == $jour ? 'selected' : '' }}>
                                        {{ ucfirst($jour) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="coach_id" class="form-select">
                                <option value="">Tous les coachs</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}" {{ request('coach_id') == $coach->id ? 'selected' : '' }}>
                                        {{ $coach->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bouton Ajouter Cours -->
            @if(auth()->user() && auth()->user()->role === 'gerant')
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterCoursModal">
                    <i class="fas fa-plus"></i> Ajouter un cours
                </button>
            </div>
            @endif

            <!-- Liste des cours -->
            <div class="row">
                @forelse ($cours as $cour)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">{{ $cour->nom }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <span class="badge bg-info">{{ ucfirst($cour->type_cours) }}</span>
                                </div>
                                <div class="time-info">
                                    <p class="card-text">
                                        <i class="fas fa-calendar-alt"></i> Date: {{ $cour->date ? $cour->date->format('d/m/Y') : 'Non définie' }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-calendar"></i> Jour: {{ \App\Http\Controllers\CoursController::convertirJourEnFrancais($cour->jour) }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-clock"></i> Heure: {{ $cour->horaire_debut ? $cour->horaire_debut->format('H:i') : 'Non définie' }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-hourglass-half"></i> Durée: {{ $cour->duree_minutes }} minutes
                                    </p>
                                </div>
                                <p class="card-text">
                                    <i class="fas fa-user-tie"></i> Coach: {{ $cour->coach->name }}
                                </p>
                                <div class="progress mb-3">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ ($cour->inscriptions->count() / $cour->capacite_max) * 100 }}%"
                                         aria-valuenow="{{ $cour->inscriptions->count() }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $cour->capacite_max }}">
                                        {{ $cour->inscriptions->count() }}/{{ $cour->capacite_max }}
                                    </div>
                                </div>
                                @if($cour->description)
                                    <p class="card-text">
                                        <small class="text-muted">{{ $cour->description }}</small>
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('cours.show', $cour) }}" class="btn btn-primary w-100">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            Aucun cours trouvé pour les critères sélectionnés.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($cours->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $cours->links() }}
                </div>
            @endif

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
                                    <label class="form-label">Coach</label>
                                    <select class="form-select" name="coach_id" required>
                                        @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Salle</label>
                                    <select class="form-select" name="salle_id" required>
                                        @foreach($salles as $salle)
                                            <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                                        @endforeach
                                    </select>
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
                                    <label class="form-label">Prix</label>
                                    <input type="number" class="form-control" name="prix" min="0" step="0.01" required>
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
        </div>
    </div>
</div>

@push('scripts')
<script>
function ajouterCours() {
    const form = document.getElementById('formAjouterCours');
    const formData = new FormData(form);

    fetch('/cours', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Erreur lors de l\'ajout du cours');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout du cours');
    });
}
</script>
@endpush
@endsection

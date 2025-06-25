@extends('layouts.app')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #E91E63, #9C27B0); min-height: 100vh; padding: 2rem;">
    <div class="container">
        <!-- En-tête -->
        <div class="text-center mb-5">
            <h1 class="text-white" style="font-family: 'Anton', sans-serif; font-size: 2.5rem;">Espace Gérant</h1>
            <h2 class="text-white" style="font-family: 'Antic', sans-serif; font-size: 1.8rem;">Gestion des Horaires</h2>
        </div>

        <!-- Messages de notification -->
        @if (session('success'))
            <div class="alert alert-success bg-white">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger bg-white">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulaire d'ajout -->
        <div class="card mb-4" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <div class="card-body p-4">
                <h3 class="card-title mb-4" style="color: #E91E63; font-family: 'Anton', sans-serif;">Ajouter un horaire</h3>
                <form action="{{ route('gerant.horaires.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="jour" class="form-select form-select-lg @error('jour') is-invalid @enderror" style="border-radius: 10px; height: 50px;" required>
                                <option value="">Sélectionnez un jour</option>
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                                <option value="Dimanche">Dimanche</option>
                            </select>
                            @error('jour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="heure_ouverture" class="form-control form-control-lg @error('heure_ouverture') is-invalid @enderror" style="border-radius: 10px; height: 50px;" required>
                            @error('heure_ouverture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="heure_fermeture" class="form-control form-control-lg @error('heure_fermeture') is-invalid @enderror" style="border-radius: 10px; height: 50px;" required>
                            @error('heure_fermeture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: #9C27B0; border: none; height: 50px; border-radius: 10px;">
                                Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des horaires -->
        <div class="card" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <div class="card-body p-4">
                <h3 class="card-title mb-4" style="color: #E91E63; font-family: 'Anton', sans-serif;">Liste des horaires</h3>
                <div class="table-responsive">
                    <table class="table table-hover custom-table">
                        <thead>
                            <tr>
                                <th>Jour</th>
                                <th>Ouverture</th>
                                <th>Fermeture</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horaires as $horaire)
                                <tr class="align-middle">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="day-indicator" style="background-color: {{ $horaire->jour === 'Samedi' || $horaire->jour === 'Dimanche' ? '#ff4081' : '#9C27B0' }}"></span>
                                            <span class="ms-2">{{ $horaire->jour }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-badge">
                                            {{ \Carbon\Carbon::parse($horaire->heure_ouverture)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-badge">
                                            {{ \Carbon\Carbon::parse($horaire->heure_fermeture)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-edit" onclick="showEditForm({{ $horaire->id }})">
                                                Modifier
                                            </button>
                                            <form action="{{ route('gerant.horaires.destroy', $horaire->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Formulaire de modification (caché par défaut) -->
                                <tr id="edit-form-{{ $horaire->id }}" class="edit-form-row" style="display: none;">
                                    <td colspan="4">
                                        <form action="{{ route('gerant.horaires.update', $horaire->id) }}" method="POST" class="edit-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <select name="jour" class="form-select custom-select" required>
                                                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour)
                                                            <option value="{{ $jour }}" {{ $horaire->jour == $jour ? 'selected' : '' }}>
                                                                {{ $jour }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="time" name="heure_ouverture" class="form-control custom-input"
                                                           value="{{ \Carbon\Carbon::parse($horaire->heure_ouverture)->format('H:i') }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="time" name="heure_fermeture" class="form-control custom-input"
                                                           value="{{ \Carbon\Carbon::parse($horaire->heure_fermeture)->format('H:i') }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-save">Enregistrer</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="empty-message">Aucun horaire défini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .day-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }

    .time-badge {
        background: #f3f4f6;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
    }

    .btn-edit {
        background: #9C27B0;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        margin-right: 5px;
    }

    .btn-delete {
        background: #E91E63;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
    }

    .btn-save {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        width: 100%;
    }

    .custom-select, .custom-input {
        border-radius: 10px;
        height: 50px;
        border: 1px solid #e5e7eb;
    }

    .empty-message {
        color: #6b7280;
        font-style: italic;
    }
</style>
@endpush

@push('scripts')
<script>
    function showEditForm(id) {
        // Cacher tous les formulaires de modification
        document.querySelectorAll('.edit-form-row').forEach(form => {
            form.style.display = 'none';
        });

        // Afficher le formulaire correspondant à l'ID
        const editForm = document.getElementById('edit-form-' + id);
        if (editForm) {
            editForm.style.display = 'table-row';
        }
    }

    // Ajouter un gestionnaire d'événements pour fermer le formulaire lors du clic sur le bouton Enregistrer
    document.querySelectorAll('.btn-save').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection

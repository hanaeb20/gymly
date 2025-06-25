@extends('layouts.app')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #E91E63, #9C27B0); min-height: 100vh; padding: 2rem;">
    <div class="container">
        <!-- En-tête -->
        <div class="text-center mb-5">
            <h1 class="text-white" style="font-family: 'Anton', sans-serif; font-size: 2.5rem;">Espace Gérant</h1>
            <h2 class="text-white" style="font-family: 'Antic', sans-serif; font-size: 1.8rem;">Gestion des Équipements</h2>
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
                <h3 class="card-title mb-4" style="color: #E91E63; font-family: 'Anton', sans-serif;">Ajouter un équipement</h3>
                <form action="{{ route('gerant.equipements.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="nom" class="form-label">Nom de l'équipement</label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="quantite" class="form-label">Quantité</label>
                            <input type="number" name="quantite" class="form-control @error('quantite') is-invalid @enderror" min="1" required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="etat" class="form-label">État</label>
                            <select name="etat" class="form-select @error('etat') is-invalid @enderror" required>
                                <option value="">Sélectionnez l'état</option>
                                <option value="Neuf">Neuf</option>
                                <option value="Bon">Bon état</option>
                                <option value="Moyen">État moyen</option>
                                <option value="Mauvais">Mauvais état</option>
                                <option value="Hors service">Hors service</option>
                            </select>
                            @error('etat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="date_achat" class="form-label">Date d'achat</label>
                            <input type="date" name="date_achat" class="form-control @error('date_achat') is-invalid @enderror" required>
                            @error('date_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100" style="background: #9C27B0; border: none; height: 40px; border-radius: 10px;">
                                Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des équipements -->
        <div class="card" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <div class="card-body p-4">
                <h3 class="card-title mb-4" style="color: #E91E63; font-family: 'Anton', sans-serif;">Liste des équipements</h3>
                <div class="table-responsive">
                    <table class="table table-hover custom-table">
                        <thead>
                            <tr>
                                <th>Équipement</th>
                                <th>Quantité</th>
                                <th>État</th>
                                <th>Date d'achat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equipements ?? [] as $equipement)
                                <tr class="align-middle">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="equipment-icon">🏋️</span>
                                            <span class="ms-2">{{ $equipement->nom }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="quantity-badge">
                                            {{ $equipement->quantite }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="state-badge" data-state="{{ $equipement->etat }}">
                                            {{ $equipement->etat }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($equipement->date_achat)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-edit" onclick="showEditForm({{ $equipement->id }})">
                                                Modifier
                                            </button>
                                            <form action="{{ route('gerant.equipements.delete', $equipement->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Formulaire de modification (caché par défaut) -->
                                <tr id="edit-form-{{ $equipement->id }}" class="edit-form-row" style="display: none;">
                                    <td colspan="5">
                                        <form action="{{ route('gerant.equipements.update', $equipement->id) }}" method="POST" class="edit-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <input type="text" name="nom" class="form-control" value="{{ $equipement->nom }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" name="quantite" class="form-control" value="{{ $equipement->quantite }}" min="1" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="etat" class="form-select" required>
                                                        @foreach(['Neuf', 'Bon', 'Moyen', 'Mauvais', 'Hors service'] as $etat)
                                                            <option value="{{ $etat }}" {{ $equipement->etat == $etat ? 'selected' : '' }}>
                                                                {{ $etat }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="date_achat" class="form-control" value="{{ $equipement->date_achat }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-save w-100">Enregistrer</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="empty-message">Aucun équipement enregistré</p>
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

<style>
    /* Styles généraux */
    .custom-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table thead th {
        background: transparent;
        color: #9C27B0;
        font-family: 'Anton', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 1rem;
        border: none;
    }

    .custom-table tbody tr {
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Badges et indicateurs */
    .equipment-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }

    .quantity-badge {
        background: #E91E63;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        font-weight: bold;
    }

    .state-badge {
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        font-weight: 500;
    }

    .state-badge[data-state="Neuf"] {
        background: #4CAF50;
        color: white;
    }

    .state-badge[data-state="Bon"] {
        background: #8BC34A;
        color: white;
    }

    .state-badge[data-state="Moyen"] {
        background: #FFC107;
        color: black;
    }

    .state-badge[data-state="Mauvais"] {
        background: #FF5722;
        color: white;
    }

    .state-badge[data-state="Hors service"] {
        background: #F44336;
        color: white;
    }

    /* Boutons */
    .btn-edit {
        background-color: #ffeb3b;
        color: #000;
        border: none;
        border-radius: 20px;
        padding: 8px 20px;
        margin-right: 10px;
        transition: all 0.2s;
    }

    .btn-delete {
        background-color: #ff4081;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 20px;
        transition: all 0.2s;
    }

    .btn-save {
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 20px;
        transition: all 0.2s;
    }

    .btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    /* Formulaire d'édition */
    .edit-form {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        margin-top: 0.5rem;
    }

    .edit-form-row {
        background: #f8f9fa !important;
    }

    .edit-form-row:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    /* Inputs et selects */
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        padding: 0.5rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #9C27B0;
        box-shadow: 0 0 0 0.25rem rgba(156, 39, 176, 0.25);
    }
</style>

<script>
function showEditForm(id) {
    document.querySelectorAll('[id^="edit-form-"]').forEach(form => {
        form.style.display = 'none';
    });
    
    const editForm = document.getElementById(`edit-form-${id}`);
    if (editForm.style.display === 'none') {
        editForm.style.display = 'table-row';
    } else {
        editForm.style.display = 'none';
    }
}
</script>
@endsection 
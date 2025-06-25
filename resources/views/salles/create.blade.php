@extends('layouts.app')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #E91E63, #9C27B0); min-height: 100vh; padding: 2rem;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: none; border-bottom: none;">
                        <h2 class="text-center" style="color: #E91E63; font-family: 'Anton', sans-serif;">Créer une nouvelle salle</h2>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('salles.store') }}">
                            @csrf

                            <!-- Informations de base -->
                            <div class="basic-info">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom de la salle</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="prix_abonnement" class="form-label">Prix de l'abonnement mensuel (en DH)</label>
                                    <input type="number" class="form-control" id="prix_abonnement" name="prix_abonnement" value="{{ old('prix_abonnement') }}" min="0" step="0.01" required>
                                </div>
                            </div>

                            <!-- Informations détaillées (initialement cachées) -->
                            <div class="detailed-info" id="detailedInfo" style="display: none;">
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse') }}" required>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="ville" class="form-label">Ville</label>
                                        <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="code_postal" class="form-label">Code postal</label>
                                        <input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ old('code_postal') }}" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton Voir plus -->
                            <div class="text-center mb-4">
                                <button type="button" class="btn btn-warning" 
                                        style="background-color: #ffeb3b; color: #000; border: none; border-radius: 20px; padding: 8px 20px;"
                                        onclick="toggleDetailedInfo()">
                                    Voir plus
                                </button>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg" 
                                        style="background: #9C27B0; border: none; padding: 0.8rem 2rem; border-radius: 10px;">
                                    Créer la salle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        border-radius: 10px;
        padding: 0.8rem;
    }
    .form-control:focus {
        border-color: #9C27B0;
        box-shadow: 0 0 0 0.25rem rgba(156, 39, 176, 0.25);
    }
    .btn-primary:hover {
        background: #7B1FA2 !important;
    }
    .btn-warning:hover {
        background-color: #ffd740 !important;
        color: #000 !important;
    }
</style>

<script>
function toggleDetailedInfo() {
    const detailedInfo = document.getElementById('detailedInfo');
    const isHidden = detailedInfo.style.display === 'none';
    
    if (isHidden) {
        detailedInfo.style.display = 'block';
        detailedInfo.style.animation = 'slideDown 0.3s ease-out';
    } else {
        detailedInfo.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => {
            detailedInfo.style.display = 'none';
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Si il y a des erreurs de validation, montrer automatiquement les champs détaillés
    @if($errors->any())
        document.getElementById('detailedInfo').style.display = 'block';
    @endif
});
</script>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}
</style>
@endsection

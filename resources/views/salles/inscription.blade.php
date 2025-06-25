@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Inscription à {{ $salle->nom }}</h2>
                    <a href="{{ route('salles.show', $salle) }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Informations de la salle</h4>
                        <p><strong>Adresse :</strong> {{ $salle->adresse }}</p>
                        <p><strong>Téléphone :</strong> {{ $salle->telephone }}</p>
                        <p><strong>Description :</strong> {{ $salle->description }}</p>
                    </div>

                    <form action="{{ route('salles.inscription.store', $salle) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <h4>Sélectionnez votre abonnement</h4>
                            @foreach($salle->abonnements as $abonnement)
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="abonnement_id"
                                           id="abonnement{{ $abonnement->id }}" value="{{ $abonnement->id }}" required>
                                    <label class="form-check-label" for="abonnement{{ $abonnement->id }}">
                                        <strong>{{ $abonnement->nom }}</strong> - {{ $abonnement->prix }}€
                                        <br>
                                        <small class="text-muted">
                                            Durée : {{ $abonnement->duree }} mois
                                            <br>
                                            {{ $abonnement->description }}
                                        </small>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <h4>Informations personnelles</h4>
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                       value="{{ Auth::user()->nom }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ Auth::user()->email }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                            </div>

                            <div class="mb-3">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4>Informations médicales</h4>
                            <div class="mb-3">
                                <label for="antecedents_medicaux" class="form-label">Antécédents médicaux</label>
                                <textarea class="form-control" id="antecedents_medicaux" name="antecedents_medicaux" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="allergies" class="form-label">Allergies</label>
                                <textarea class="form-control" id="allergies" name="allergies" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="conditions" name="conditions" required>
                                <label class="form-check-label" for="conditions">
                                    J'accepte les conditions générales d'utilisation
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Confirmer l'inscription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 15px;
    }

    .card-header {
        background: linear-gradient(45deg, #DE2A80, #580D31);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    .info-salle {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #333;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 0.75rem 1rem;
    }

    .form-control:focus {
        border-color: #DE2A80;
        box-shadow: 0 0 0 0.2rem rgba(222, 42, 128, 0.25);
    }

    .btn-primary {
        background: linear-gradient(45deg, #DE2A80, #580D31);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(222, 42, 128, 0.3);
    }

    .btn-light {
        background-color: rgba(255, 255, 255, 0.9);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background-color: white;
        transform: translateY(-2px);
    }

    h4 {
        color: #DE2A80;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

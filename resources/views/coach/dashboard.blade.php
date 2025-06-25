
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de bord du coach</h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestion des cours</h5>
                    <p class="card-text">Créez et gérez vos cours.</p>
                    <a href="{{ route('coach.cours') }}" class="btn btn-primary">Voir mes cours</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Réservations</h5>
                    <p class="card-text">Gérez les réservations pour vos cours.</p>
                    <a href="{{ route('coach.reservations') }}" class="btn btn-primary">Voir les réservations</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profil</h5>
                    <p class="card-text">Modifiez vos informations personnelles.</p>
                    <a href="{{ route('coach.profile') }}" class="btn btn-primary">Modifier mon profil</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mes prochains cours</h5>
                </div>
                <div class="card-body">
                    @if($coach->cours->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Places disponibles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coach->cours->sortBy('date')->take(5) as $cours)
                                        <tr>
                                            <td>{{ $cours->titre }}</td>
                                            <td>{{ $cours->date->format('d/m/Y') }}</td>
                                            <td>{{ $cours->heure_debut }} - {{ $cours->heure_fin }}</td>
                                            <td>{{ $cours->places_disponibles }}</td>
                                            <td>
                                                <a href="{{ route('coach.cours.edit', $cours->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">Vous n'avez pas encore créé de cours.</p>
                    @endif
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

    .btn-primary {
        background: linear-gradient(45deg, #DE2A80, #580D31);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection

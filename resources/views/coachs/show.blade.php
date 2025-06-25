@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Profil du Coach</h2>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('images/coach.jpeg') }}" alt="Photo du coach" class="img-fluid rounded-circle" style="max-width: 150px;">
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $coach->name }}</h3>
                            <p class="text-muted">{{ $coach->specialite }}</p>
                            <p><i class="fas fa-envelope"></i> {{ $coach->email }}</p>
                            <p><i class="fas fa-phone"></i> {{ $coach->telephone }}</p>
                            @if($coach->salle)
                                <p><i class="fas fa-building"></i> Salle : {{ $coach->salle->nom }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Expérience</h4>
                            <p>{{ $coach->experience }}</p>
                        </div>
                        <div class="col-md-6">
                            <h4>Spécialité</h4>
                            <p>{{ $coach->specialite }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4>Cours dispensés</h4>
                        <div class="list-group">
                            @foreach($coach->cours as $cours)
                                <a href="{{ route('cours.show', $cours) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $cours->nom }}</h5>
                                        <small>{{ $cours->jour_semaine }} à {{ $cours->heure_debut }}</small>
                                    </div>
                                    <p class="mb-1">{{ $cours->description }}</p>
                                    <small>Capacité: {{ $cours->capacite }} participants</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
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

    h2, h3, h4 {
        color: #DE2A80;
    }

    .list-group-item {
        border: none;
        margin-bottom: 10px;
        border-radius: 10px !important;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(222, 42, 128, 0.1);
    }

    .img-fluid {
        border: 3px solid #DE2A80;
        padding: 5px;
    }

    i {
        color: #DE2A80;
        margin-right: 10px;
    }
</style>
@endsection

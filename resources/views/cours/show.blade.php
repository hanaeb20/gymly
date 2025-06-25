@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">{{ $cours->nom }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge bg-info">{{ ucfirst($cours->type_cours) }}</span>
                        <span class="badge bg-secondary">{{ ucfirst($cours->niveau) }}</span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-tie"></i> Coach</h5>
                            <p>{{ $cours->coach->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-building"></i> Salle</h5>
                            <p>{{ $cours->salle->nom }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-clock"></i> Horaires</h5>
                            <p>{{ $cours->horaire_debut->format('H:i') }} - {{ $cours->horaire_fin->format('H:i') }}</p>
                            <p>Durée : {{ $cours->duree_minutes }} minutes</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-users"></i> Capacité</h5>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ ($cours->inscriptions->count() / $cours->capacite_max) * 100 }}%"
                                     aria-valuenow="{{ $cours->inscriptions->count() }}"
                                     aria-valuemin="0"
                                     aria-valuemax="{{ $cours->capacite_max }}">
                                    {{ $cours->inscriptions->count() }}/{{ $cours->capacite_max }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($cours->description)
                        <div class="mb-4">
                            <h5><i class="fas fa-info-circle"></i> Description</h5>
                            <p>{{ $cours->description }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5><i class="fas fa-tag"></i> Prix</h5>
                        <p>{{ number_format($cours->prix, 2) }} €</p>
                    </div>

                    @auth
                        @if(Auth::user()->role === 'client')
                            @if($cours->inscriptions->count() < $cours->capacite_max)
                                <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cours_id" value="{{ $cours->id }}">
                                    <input type="hidden" name="date" value="{{ $cours->horaire_debut->format('Y-m-d') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-calendar-plus"></i> Réserver ce cours
                                    </button>
                                </form>

                                <script>
                                    document.getElementById('reservationForm').addEventListener('submit', function(e) {
                                        e.preventDefault();

                                        const formData = new FormData(this);
                                        const data = {};
                                        formData.forEach((value, key) => {
                                            data[key] = value;
                                        });

                                        fetch('{{ route("reservations.store") }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                                                window.location.href = '{{ route("reservations.index") }}';
                                            } else {
                                                alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
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
                                    });
                                </script>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Ce cours est complet
                                </div>
                            @endif
                        @elseif(Auth::user()->role === 'coach')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> En tant que coach, vous ne pouvez pas réserver des cours
                            </div>
                        @elseif(Auth::user()->role === 'gerant')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> En tant que gérant, vous ne pouvez pas réserver des cours
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour réserver ce cours
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

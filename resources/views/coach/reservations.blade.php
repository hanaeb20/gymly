@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des réservations</h1>
        @if(auth()->user()->role === 'client')
            @if($reservations->isNotEmpty())
                @php
                    $firstReservation = $reservations->first();
                    $coach = $firstReservation && $firstReservation->cours ? $firstReservation->cours->coach : null;
                @endphp
                @if($coach)
                    <a href="{{ route('evaluations.create', $coach) }}" class="btn btn-primary">
                        <i class="fas fa-star"></i> Évaluer le coach
                    </a>
                @endif
            @endif
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <div class="alert alert-info">
            Aucune réservation trouvée.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        @if($reservation->client && $reservation->cours)
                            <tr>
                                <td>{{ $reservation->cours->nom }}</td>
                                <td>{{ $reservation->client->name }}</td>
                                <td>{{ $reservation->date_reservation->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $reservation->statut === 'confirmee' ? 'success' : ($reservation->statut === 'en_attente' ? 'warning' : 'danger') }}">
                                        {{ $reservation->statut }}
                                    </span>
                                </td>
                                <td>
                                    @if($reservation->cours->coach_id === auth()->id())
                                        <form action="{{ route('coach.reservations.update', $reservation->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="en_attente" {{ $reservation->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                <option value="confirmee" {{ $reservation->statut === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                                <option value="annulee" {{ $reservation->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

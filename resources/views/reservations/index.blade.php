@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mes Réservations</h1>
                <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
                    <i class="fas fa-star"></i> Évaluer un coach
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Coach</th>
                                    <th>Date</th>
                                    <th>Horaire</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->cours->nom }}</td>
                                        <td>{{ $reservation->cours->coach->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($reservation->cours->horaire_debut)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($reservation->cours->horaire_fin)->format('H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $reservation->statut === 'confirmée' ? 'success' : ($reservation->statut === 'en_attente' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($reservation->statut) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($reservation->statut === 'en_attente')
                                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                        <i class="fas fa-times"></i> Annuler
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info">
                                                Vous n'avez aucune réservation pour le moment.
                                            </div>
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
</div>
@endsection

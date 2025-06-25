@extends('layouts.app')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #E91E63, #9C27B0); min-height: 100vh; padding: 2rem;">
    <div class="container">
        <div class="card" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <div class="card-body p-4">
                <h1 class="card-title mb-4" style="color: #E91E63; font-family: 'Anton', sans-serif;">{{ $salle->nom }}</h1>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section mb-4">
                            <h3 style="color: #9C27B0; font-family: 'Anton', sans-serif;">Informations générales</h3>
                            <p><strong>Adresse:</strong> {{ $salle->adresse }}</p>
                            <p><strong>Ville:</strong> {{ $salle->ville }}</p>
                            <p><strong>Code postal:</strong> {{ $salle->code_postal }}</p>
                            <p><strong>Téléphone:</strong> {{ $salle->telephone }}</p>
                            <p><strong>Email:</strong> {{ $salle->email }}</p>
                            <p><strong>Prix abonnement:</strong> {{ $salle->prix_abonnement }} DH</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-section mb-4">
                            <h3 style="color: #9C27B0; font-family: 'Anton', sans-serif;">Horaires d'ouverture</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Jour</th>
                                            <th>Ouverture</th>
                                            <th>Fermeture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(is_string($salle->horaires_ouverture))
                                            @php
                                                $horaires = json_decode($salle->horaires_ouverture, true);
                                            @endphp
                                            @if($horaires)
                                                @foreach($horaires as $jour => $heures)
                                                    <tr>
                                                        <td>{{ ucfirst($jour) }}</td>
                                                        <td>{{ $heures[0] }}</td>
                                                        <td>{{ $heures[1] }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="info-section">
                            <h3 style="color: #9C27B0; font-family: 'Anton', sans-serif;">Équipements disponibles</h3>
                            <div class="equipment-list">
                                @if(is_string($salle->equipements))
                                    @php
                                        $equipements = json_decode($salle->equipements, true);
                                    @endphp
                                    @if($equipements && count($equipements) > 0)
                                        <ul class="list-unstyled row">
                                            @foreach($equipements as $equipement)
                                                <li class="col-md-4 mb-2">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    {{ $equipement }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Aucun équipement enregistré</p>
                                    @endif
                                @else
                                    <p>Aucun équipement enregistré</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::check() && Auth::user()->role === 'gerant' && Auth::id() === $salle->gerant_id)
                    <div class="mt-4">
                        <a href="{{ route('salles.edit', $salle) }}" class="btn btn-primary"
                           style="background: #9C27B0; border: none; border-radius: 10px;">
                            Modifier la salle
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section des annonces -->
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Annonces</h4>
                </div>
                <div class="card-body">
                    @if($salle->annonces->where('est_active', true)->where('date_fin', '>=', now())->count() > 0)
                        @foreach($salle->annonces->where('est_active', true)->where('date_fin', '>=', now()) as $annonce)
                            <div class="annonce mb-3 p-3 border rounded">
                                <h5 class="text-primary">{{ $annonce->titre }}</h5>
                                <p class="mb-1">{{ $annonce->contenu }}</p>
                                <small class="text-muted">
                                    Du {{ $annonce->date_debut->format('d/m/Y') }} au {{ $annonce->date_fin->format('d/m/Y') }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Aucune annonce active pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-section {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }
    .table th {
        background: #f8f9fa;
        color: #9C27B0;
        font-family: 'Anton', sans-serif;
    }
    .equipment-list li {
        font-size: 1.1rem;
        padding: 0.5rem;
    }
    .btn-primary:hover {
        background: #7B1FA2 !important;
    }
</style>
@endsection

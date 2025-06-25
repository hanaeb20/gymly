@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i>
                Détails du membre
            </h5>
            <a href="{{ route('gerant.inscriptions.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations personnelles</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted">Nom complet</label>
                                <p class="mb-0">{{ $user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">Email</label>
                                <p class="mb-0">{{ $user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">Téléphone</label>
                                <p class="mb-0">{{ $user->telephone }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">Adresse</label>
                                <p class="mb-0">{{ $user->adresse }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations d'inscription</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted">Date d'inscription</label>
                                <p class="mb-0">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">Statut</label>
                                <p class="mb-0">
                                    <span class="badge bg-success">Inscrit</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('gerant.inscriptions.refuser', $user->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir désinscrire ce membre ?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-user-minus me-2"></i>Désinscrire le membre
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
</style>
@endpush
@endsection

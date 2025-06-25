@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Détails de la Déclaration</h2>
                    <a href="{{ route('gerant.declarations.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="declaration-details">
                        <div class="mb-4">
                            <h4>Informations</h4>
                            <p><strong>Date :</strong> {{ $declaration->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Expéditeur :</strong> {{ $declaration->user->name }}</p>
                            <p><strong>Salle :</strong> {{ $declaration->salle->nom }}</p>
                            <p><strong>Statut :</strong>
                                <span class="badge bg-{{ $declaration->statut === 'en_attente' ? 'warning' : ($declaration->statut === 'lu' ? 'info' : 'success') }}">
                                    {{ $declaration->statut }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-4">
                            <h4>Sujet</h4>
                            <p class="sujet">{{ $declaration->sujet }}</p>
                        </div>

                        <div class="mb-4">
                            <h4>Message</h4>
                            <div class="message-content">
                                {{ $declaration->message }}
                            </div>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('gerant.declarations.update', $declaration) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour le statut
                                    </button>
                            </form>
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

    .declaration-details h4 {
        color: #DE2A80;
        margin-bottom: 1rem;
    }

    .sujet {
        font-size: 1.2rem;
        font-weight: 500;
        color: #333;
    }

    .message-content {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        white-space: pre-wrap;
    }

    .badge {
        padding: 0.5em 1em;
        font-size: 0.85em;
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

    .form-select {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 0.75rem 1rem;
    }

    .form-select:focus {
        border-color: #DE2A80;
        box-shadow: 0 0 0 0.2rem rgba(222, 42, 128, 0.25);
    }
</style>
@endsection

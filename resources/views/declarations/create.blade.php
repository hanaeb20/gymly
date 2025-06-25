@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Nouvelle Déclaration</h2>
                    <a href="{{ route('declarations.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('declarations.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="salle_id" class="form-label">Salle concernée</label>
                            <select name="salle_id" id="salle_id" class="form-select @error('salle_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez une salle</option>
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                        {{ $salle->nom }} - {{ $salle->adresse }}, {{ $salle->ville }}
                                    </option>
                                @endforeach
                            </select>
                            @error('salle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="sujet" class="form-label">Sujet de votre déclaration</label>
                            <input type="text" name="sujet" id="sujet"
                                   class="form-control @error('sujet') is-invalid @enderror"
                                   value="{{ old('sujet') }}"
                                   placeholder="Ex: Problème avec les équipements, Question sur les horaires..."
                                   required>
                            @error('sujet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Votre message</label>
                            <textarea name="message" id="message" rows="6"
                                      class="form-control @error('message') is-invalid @enderror"
                                      placeholder="Décrivez votre problème ou votre question en détail..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer la déclaration
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

    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #DE2A80;
        box-shadow: 0 0 0 0.2rem rgba(222, 42, 128, 0.25);
    }

    .form-control::placeholder {
        color: #999;
    }

    .btn-primary {
        background: linear-gradient(45deg, #DE2A80, #580D31);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-size: 1rem;
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

    .invalid-feedback {
        font-size: 0.875rem;
        color: #dc3545;
        margin-top: 0.25rem;
    }
</style>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Déclarations</h2>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Expéditeur</th>
                                    <th>Salle</th>
                                    <th>Sujet</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($declarations as $declaration)
                                    <tr>
                                        <td>{{ $declaration->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $declaration->user->name }}</td>
                                        <td>{{ $declaration->salle->nom }}</td>
                                        <td>{{ $declaration->sujet }}</td>
                                        <td>
                                            <span class="badge bg-{{ $declaration->statut === 'en_attente' ? 'warning' : ($declaration->statut === 'lu' ? 'info' : 'success') }}">
                                                {{ $declaration->statut }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('gerant.declarations.show', $declaration) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info">
                                                Aucune déclaration n'a été reçue.
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

    .table {
        margin-bottom: 0;
    }

    .badge {
        padding: 0.5em 1em;
        font-size: 0.85em;
    }

    .btn-primary {
        background: linear-gradient(45deg, #DE2A80, #580D31);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(222, 42, 128, 0.3);
    }

    .table th {
        font-weight: 600;
        color: #333;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endsection

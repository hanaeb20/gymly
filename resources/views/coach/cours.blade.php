@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes cours</h1>
        <a href="{{ route('coach.cours.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau cours
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places disponibles</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cours as $cour)
                    <tr>
                        <td>{{ $cour->titre }}</td>
                        <td>{{ $cour->date->format('d/m/Y') }}</td>
                        <td>{{ $cour->heure_debut }} - {{ $cour->heure_fin }}</td>
                        <td>{{ $cour->places_disponibles }}</td>
                        <td>{{ $cour->prix }} €</td>
                        <td>
                            <a href="{{ route('coach.cours.edit', $cour->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('coach.cours.destroy', $cour->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

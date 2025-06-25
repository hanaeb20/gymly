@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des coachs</h5>
                    <a href="{{ route('gerant.coachs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un coach
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Spécialité</th>
                                    <th>Expérience</th>
                                    <th>Code d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coachs as $coach)
                                    <tr>
                                        <td>{{ $coach->name }}</td>
                                        <td>{{ $coach->email }}</td>
                                        <td>{{ $coach->telephone }}</td>
                                        <td>{{ $coach->specialite }}</td>
                                        <td>{{ $coach->experience }}</td>
                                        <td>
                                            @if($coach->inscription_code)
                                                <span class="badge bg-info">{{ $coach->inscription_code }}</span>
                                                @if(!$coach->email)
                                                    <span class="badge bg-warning">Non inscrit</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Code non généré</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('gerant.coachs.edit', $coach) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('gerant.coachs.destroy', $coach) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce coach ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @if($coach->inscription_code)
                                                    <form action="{{ route('gerant.coachs.regenerate-code', $coach) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Êtes-vous sûr de vouloir générer un nouveau code ?')">
                                                            <i class="fas fa-sync"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

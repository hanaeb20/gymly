@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des cours</h5>
                    <div>
                        <a href="{{ route('gerant.cours.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un cours
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total des cours</h5>
                                    <p class="card-text display-4">{{ $cours->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Cours cette semaine</h5>
                                    <p class="card-text display-4">{{ $cours->where('jour', now()->format('l'))->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Capacité totale</h5>
                                    <p class="card-text display-4">{{ $cours->sum('capacite') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Types de cours</h5>
                                    <p class="card-text display-4">{{ $cours->pluck('type_cours')->unique()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('gerant.cours.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select name="type_cours" class="form-select">
                                        <option value="">Tous les types</option>
                                        @foreach($types_cours as $type)
                                            <option value="{{ $type }}" {{ request('type_cours') == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="jour" class="form-select">
                                        <option value="">Tous les jours</option>
                                        @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                            <option value="{{ $jour }}" {{ request('jour') == $jour ? 'selected' : '' }}>
                                                {{ ucfirst($jour) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="coach_id" class="form-select">
                                        <option value="">Tous les coachs</option>
                                        @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" {{ request('coach_id') == $coach->id ? 'selected' : '' }}>
                                                {{ $coach->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tableau des cours -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Coach</th>
                                    <th>Jour</th>
                                    <th>Heure de début</th>
                                    <th>Durée</th>
                                    <th>Capacité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cours as $cour)
                                    <tr>
                                        <td>{{ $cour->nom }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($cour->type_cours) }}</span>
                                        </td>
                                        <td>{{ $cour->coach->name }}</td>
                                        <td>{{ \App\Http\Controllers\CoursController::convertirJourEnFrancais($cour->jour) }}</td>
                                        <td>{{ $cour->heure_debut }}</td>
                                        <td>{{ $cour->duree }} minutes</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ ($cour->inscriptions->count() / $cour->capacite) * 100 }}%"
                                                     aria-valuenow="{{ $cour->inscriptions->count() }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="{{ $cour->capacite }}">
                                                    {{ $cour->inscriptions->count() }}/{{ $cour->capacite }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('gerant.cours.edit', $cour) }}" class="btn btn-sm btn-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('gerant.cours.show', $cour) }}" class="btn btn-sm btn-info" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('gerant.cours.destroy', $cour) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Aucun cours trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($cours->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $cours->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Section Annonce et Photos -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Annonce et Photos de la salle</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAnnonceModal">
                        <i class="fas fa-edit"></i> Modifier l'annonce
                    </button>
    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Description actuelle :</h6>
                            <p>{{ $salle->description ?? 'Aucune description disponible' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Photos de la salle :</h6>
                            <div class="row">
                                @if($salle->photos->count() > 0)
                                    @foreach($salle->photos as $photo)
                                        <div class="col-6 mb-3">
                                            @php
                                                $fullPath = 'storage/' . $photo->chemin;
                                                $exists = Storage::disk('public')->exists($photo->chemin);
                                            @endphp
                                            @if($exists)
                                                <img src="{{ url($fullPath) }}" class="img-fluid rounded" alt="Photo de la salle">

                                            @else
                                                <div class="alert alert-warning">
                                                    <p>Image non trouvée : {{ $photo->chemin }}</p>
                                                    <p>Chemin complet : {{ $fullPath }}</p>
                                                    <p>Existe dans le stockage : {{ $exists ? 'Oui' : 'Non' }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p>Aucune photo disponible</p>
                                @endif
                            </div>
                            <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addPhotoModal">
                                <i class="fas fa-plus"></i> Ajouter une photo
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal d'édition de l'annonce -->
    <div class="modal fade" id="editAnnonceModal" tabindex="-1" aria-labelledby="editAnnonceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAnnonceModalLabel">Modifier l'annonce</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gerant.salles.update-annonce', $salle) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ $salle->description ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de photo -->
    <div class="modal fade" id="addPhotoModal" tabindex="-1" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPhotoModalLabel">Ajouter une photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gerant.salles.add-photo', $salle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row">
        <!-- Cartes de statistiques -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Membres</h5>
                    <h2 class="card-text">{{ $membresCount }}</h2>
                    <a href="{{ route('gerant.inscriptions.index') }}" class="text-white">Voir les inscriptions</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Coachs</h5>
                    <h2 class="card-text"></h2>
                    <a href="{{ route('gerant.coachs.index') }}" class="text-white">Gérer les coachs</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Matériel</h5>
                    <h2 class="card-text"></h2>
                    <a href="{{ route('gerant.materiel.index') }}" class="text-white">Gérer le matériel</a>
                        </div>
                        </div>
                    </div>


    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('gerant.coachs.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus"></i> Ajouter un coach
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('gerant.horaires.index') }}" class="btn btn-success w-100">
                                <i class="fas fa-clock"></i> Gérer les horaires
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('gerant.materiel.index') }}" class="btn btn-info w-100">
                                <i class="fas fa-dumbbell"></i> Gérer le matériel
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('gerant.inscriptions.index') }}" class="btn btn-warning w-100">
                                <i class="fas fa-clipboard-list"></i> Voir les inscriptions
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('gerant.declarations.index') }}" class="btn btn-danger w-100">
                                <i class="fas fa-envelope"></i> Voir les déclarations
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Nos Salles de Sport</h1>
        <p class="lead text-muted">Découvrez nos salles équipées et nos services adaptés à vos besoins</p>
    </div>

    <div class="row g-4">
        @foreach($salles as $salle)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm hover-shadow transition">
                @php
                    $photos = $salle->photos;
                    \Log::info('Photos pour la salle ' . $salle->id . ':', ['photos' => $photos->toArray()]);
                @endphp
                @if($photos->count() > 0)
                    <div id="carousel-{{ $salle->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($photos as $index => $photo)
                                <button type="button" data-bs-target="#carousel-{{ $salle->id }}"
                                        data-bs-slide-to="{{ $index }}"
                                        class="{{ $index === 0 ? 'active' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="card-img-wrapper">
                                        <img src="{{ asset('storage/' . $photo->chemin) }}"
                                             class="d-block w-100"
                                             alt="Photo {{ $index + 1 }} de {{ $salle->nom }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($photos->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $salle->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $salle->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        @endif
                    </div>
                @elseif($salle->photo_couverture)
                    <div class="card-img-wrapper">
                        <img src="{{ asset('storage/' . $salle->photo_couverture) }}" class="card-img-top" alt="{{ $salle->nom }}">
                    </div>
                @else
                    <div class="card-img-wrapper bg-light">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">{{ $salle->nom }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($salle->description, 100) }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-users text-primary me-2"></i>
                        <span class="text-muted">Capacité: {{ $salle->capacite }} personnes</span>
                    </div>

                    @if(auth()->check())
                        @if($salle->isInscrit(auth()->id()))
                            <div class="d-flex gap-2">
                                <button class="btn btn-success flex-grow-1" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Déjà Inscrit
                                </button>
                                <form action="{{ route('salles.inscription.destroy', $salle->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-times-circle me-2"></i>Annuler
                                    </button>
                                </form>
                            </div>
                        @elseif(auth()->user()->role === 'client')
                            <form action="{{ route('salles.inscription', $salle->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus me-2"></i>S'inscrire
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Connectez-vous pour vous inscrire
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card-img-wrapper {
        height: 250px;
        overflow: hidden;
        position: relative;
    }
    .card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .card:hover .card-img-wrapper img {
        transform: scale(1.05);
    }
    .transition {
        transition: all 0.3s ease;
    }
    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .carousel:hover .carousel-control-prev,
    .carousel:hover .carousel-control-next {
        opacity: 0.8;
    }
    .carousel-indicators {
        margin-bottom: 0.5rem;
    }
    .carousel-indicators button {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin: 0 4px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button');
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
            });
        });

        // Initialiser tous les carousels
        const carousels = document.querySelectorAll('.carousel');
        carousels.forEach(carousel => {
            new bootstrap.Carousel(carousel, {
                interval: 5000,
                wrap: true
            });
        });
    });
</script>
@endpush
@endsection

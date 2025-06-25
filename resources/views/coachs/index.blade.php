@extends('layouts.app')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #E91E63, #9C27B0); min-height: 100vh; padding: 2rem;">
    <div class="container">
        <div class="card" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <div class="card-body p-4">
                <h2 class="card-title mb-4" style="color: #E91E63; font-family: 'Anton', sans-serif;">Nos Coachs</h2>

                @if($coachs->isEmpty())
                    <p class="text-center">Aucun coach disponible pour le moment.</p>
                @else
                    <div class="row g-4">
                        @foreach($coachs as $coach)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img src="{{ $coach->photo_profil ? Storage::url($coach->photo_profil) : 'https://via.placeholder.com/150' }}"
                                                 alt="{{ $coach->name }}"
                                                 class="rounded-circle"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                        <h5 class="card-title">{{ $coach->name }}</h5>
                                        <p class="card-text">{{ $coach->specialite ?? 'Coach sportif' }}</p>
                                        <a href="{{ route('coachs.show', $coach) }}" class="btn btn-primary">
                                            Voir le profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .btn-primary {
        background: #9C27B0;
        border: none;
        border-radius: 10px;
    }
    .btn-primary:hover {
        background: #7B1FA2;
    }
</style>
@endsection

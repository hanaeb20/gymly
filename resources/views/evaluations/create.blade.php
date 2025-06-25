@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/evaluations.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <!-- Sélection du coach -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card coach-card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Sélectionner un coach</h2>
                </div>
                <div class="card-body">
                    <form id="coachSelector" class="row g-3">
                        <div class="col-md-6">
                            <select class="form-select" id="coachSelect" onchange="if(this.value) window.location.href = '{{ route('evaluations.create.coach', '') }}/' + this.value">
                                <option value="">Choisir un coach...</option>
                                @foreach($coaches as $c)
                                        <option value="{{ $c->id() }}" {{ isset($coach) && $coach && $coach->id() == $c->id() ? 'selected' : '' }}>
                                        {{ $c->name }} - {{ $c->specialite }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($coach) && $coach)
    <div class="row">
        <div class="col-md-8">
            <!-- Formulaire d'évaluation -->
            <div class="card mb-4 evaluation-form">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Évaluer {{ $coach->name }}</h2>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('evaluations.store', $coach) }}" method="POST" id="evaluationForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="note">Note</label>
                            <div class="rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="note" id="star{{ $i }}" value="{{ $i }}" {{ old('note') == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}" class="rating-label">★</label>
                                @endfor
                                <span class="rating-value" id="ratingValue"></span>
                            </div>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="commentaire">Commentaire</label>
                            <textarea class="form-control @error('commentaire') is-invalid @enderror"
                                      id="commentaire"
                                      name="commentaire"
                                      rows="4"
                                      required>{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Envoyer l'évaluation</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations du coach -->
            <div class="card mb-4 coach-card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Informations du coach</h3>
                </div>
                <div class="card-body">
                    <h4>{{ $coach->name }}</h4>
                    <p class="text-muted">{{ $coach->specialite }}</p>
                    <p>{{ $coach->experience }} d'expérience</p>
                </div>
            </div>





                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mois</th>
                                        <th>Moyenne</th>
                                        <th>Nombre d'évaluations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evolutionStats as $month => $stats)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                            <td>{{ number_format($stats['moyenne'], 1) }}</td>
                                            <td>{{ $stats['total'] }}</td>
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

    <!-- Avis des autres utilisateurs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Avis des utilisateurs</h3>
                </div>
                <div class="card-body">
                    @php
                        $evaluations = $coach->evaluations->sortByDesc('created_at');
                    @endphp

                    

                    <!-- Tableau des avis -->
                    <h4 class="mb-3">Derniers avis</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-evaluations">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $evaluation)
                                    <tr>
                                        <td>{{ $evaluation->client->name }}</td>
                                        <td>{{ $evaluation->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="rating-display">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="rating-star {{ $i <= $evaluation->note ? 'active' : '' }}">★</span>
                                                @endfor
                                                <span class="rating-value">({{ $evaluation->note }}/5)</span>
                                            </div>
                                        </td>
                                        <td class="comment-cell">{{ $evaluation->commentaire }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="alert alert-info">
                                                Aucun avis pour ce coach.
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
@endif
@endsection

@section('scripts')
@if(isset($coach) && $coach)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les étoiles de notation
    const ratingInputs = document.querySelectorAll('.rating input');
    const ratingLabels = document.querySelectorAll('.rating label');
    const ratingValue = document.getElementById('ratingValue');

    function updateRatingDisplay() {
        const selectedRating = document.querySelector('input[name="note"]:checked');
        if (selectedRating) {
            const value = selectedRating.value;
            ratingValue.textContent = `(${value}/5)`;

            ratingLabels.forEach((label, index) => {
                if (index < 5 - value) {
                    label.classList.add('active');
                } else {
                    label.classList.remove('active');
                }
            });
        } else {
            ratingValue.textContent = '';
            ratingLabels.forEach(label => label.classList.remove('active'));
        }
    }

    ratingInputs.forEach((input) => {
        input.addEventListener('change', updateRatingDisplay);
    });

    // Initialiser l'affichage si une note est déjà sélectionnée
    updateRatingDisplay();

    // Réinitialiser le formulaire si l'évaluation a été enregistrée avec succès
    @if(session('success'))
        document.getElementById('evaluationForm').reset();
        updateRatingDisplay();
    @endif

    const ctx = document.getElementById('evolutionChart').getContext('2d');
    const evolutionData = @json($evolutionStats);

    const labels = Object.keys(evolutionData).map(month => {
        const date = new Date(month + '-01');
        return date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
    });

    const data = {
        labels: labels,
        datasets: [{
            label: 'Moyenne des notes',
            data: Object.values(evolutionData).map(stats => stats.moyenne),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Évolution des notes'
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection

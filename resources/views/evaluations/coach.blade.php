@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Évaluations de {{ $coach->name }}</h2>
                </div>
                <div class="card-body">
                    @if($evaluations->isEmpty())
                        <p class="text-center">Aucune évaluation pour ce coach.</p>
                    @else
                        <div class="list-group">
                            @foreach($evaluations as $evaluation)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">Note : {{ $evaluation->note }}/5</h5>
                                            <p class="mb-1">{{ $evaluation->commentaire }}</p>
                                            <small class="text-muted">Par {{ $evaluation->client->name }} le {{ $evaluation->created_at->format('d/m/Y') }}</small>
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
</div>
@endsection

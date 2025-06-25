@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <!-- Filtres -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <form action="{{ route('salles.search') }}" method="GET" style="display: flex; gap: 16px;">
            <div style="flex: 1;">
                <select name="sport" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Tous les sports</option>
                    <option value="musculation" {{ request('sport') == 'musculation' ? 'selected' : '' }}>Musculation</option>
                    <option value="crossfit" {{ request('sport') == 'crossfit' ? 'selected' : '' }}>CrossFit</option>
                    <option value="fitness" {{ request('sport') == 'fitness' ? 'selected' : '' }}>Fitness</option>
                    <option value="boxe" {{ request('sport') == 'boxe' ? 'selected' : '' }}>Boxe</option>
                    <option value="yoga" {{ request('sport') == 'yoga' ? 'selected' : '' }}>Yoga</option>
                </select>
            </div>
            <div style="flex: 1;">
                <input type="text" name="ville" value="{{ request('ville') }}" placeholder="Ville" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="flex: 1;">
                <select name="public" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Tout public</option>
                    <option value="homme" {{ request('public') == 'homme' ? 'selected' : '' }}>Homme</option>
                    <option value="femme" {{ request('public') == 'femme' ? 'selected' : '' }}>Femme</option>
                    <option value="enfant" {{ request('public') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                </select>
            </div>
            <button type="submit" style="background: #DE2A80; color: white; border: none; padding: 8px 24px; border-radius: 4px; cursor: pointer;">
                Mettre à jour
            </button>
        </form>
    </div>

    <!-- Résultats -->
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 20px;">
        <!-- Carte -->
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: calc(100vh - 200px); position: sticky; top: 20px;">
            <div style="width: 100%; height: 100%; background: #f0f0f0; border-radius: 4px;">
                [Carte interactive ici]
            </div>
        </div>

        <!-- Liste des salles -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @forelse($salles as $salle)
            <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; display: flex;">
                <!-- Image de la salle -->
                <div style="width: 300px; height: 200px; flex-shrink: 0;">
                    <img src="{{ asset($salle->image) }}" alt="{{ $salle->nom }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>

                <!-- Informations de la salle -->
                <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <h2 style="margin: 0; font-family: 'Anta', sans-serif; color: #2B2051;">{{ $salle->nom }}</h2>
                            <div style="background: #DE2A80; color: white; padding: 4px 12px; border-radius: 16px; font-size: 14px;">
                                {{ $salle->categorie }}
                            </div>
                        </div>
                        <p style="color: #666; margin: 8px 0;">{{ $salle->adresse }}, {{ $salle->ville }}</p>
                        <div style="display: flex; gap: 8px; margin-top: 12px;">
                            @foreach($salle->equipements as $equipement)
                            <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 16px; font-size: 14px;">
                                {{ $equipement }}
                            </span>
                            @endforeach
                        </div>
                        <p style="margin: 12px 0;">{{ Str::limit($salle->description, 150) }}</p>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                        <div>
                            <div style="font-size: 24px; font-weight: bold; color: #2B2051;">{{ $salle->prix }}€</div>
                            <div style="color: #666; font-size: 14px;">par mois</div>
                        </div>
                        <a href="{{ route('salles.show', $salle) }}" style="background: #2B2051; color: white; padding: 8px 24px; border-radius: 4px; text-decoration: none; font-family: 'Anton', sans-serif;">
                            Voir les détails
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div style="background: white; padding: 40px; border-radius: 8px; text-align: center;">
                <h2 style="color: #2B2051; margin: 0;">Aucune salle trouvée</h2>
                <p style="color: #666; margin-top: 8px;">Essayez de modifier vos critères de recherche</p>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($salles->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 20px;">
                {{ $salles->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

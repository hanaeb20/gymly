@forelse($salles as $salle)
    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.1);">
        <img src="https://placehold.co/400x300/DE2A80/ffffff?text={{ urlencode($salle->nom) }}" alt="{{ $salle->nom }}" style="width: 100%; height: 200px; object-fit: cover;">
        <div style="padding: 24px;">
            <h3 style="color: #2B2051; font-family: 'Anton', sans-serif; font-size: 24px; margin-bottom: 8px;">{{ $salle->nom }}</h3>
            <p style="color: #666; font-family: 'Antic', sans-serif; margin-bottom: 8px;">{{ $salle->ville }}</p>
            <p style="color: #666; font-family: 'Antic', sans-serif; margin-bottom: 16px;">Catégorie: {{ $salle->categorie->nom ?? 'Non spécifiée' }}</p>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('salles.show', $salle) }}" style="color: #DE2A80; text-decoration: none; font-family: 'Anton', sans-serif;">
                    Voir les détails
                </a>
                @auth
                    @if(Auth::user()->role === 'client')
                        <form action="{{ route('salles.inscription', $salle) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: #2B2051; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-family: 'Anta', sans-serif; cursor: pointer;">
                                S'inscrire
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@empty
    <div style="grid-column: 1 / -1; text-align: center; color: white; font-family: 'Antic', sans-serif; font-size: 18px;">
        Aucune salle trouvée pour la catégorie et la ville sélectionnées. Essayez de modifier vos critères de recherche.
    </div>
@endforelse

@if(method_exists($salles, 'links'))
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $salles->links() }}
    </div>
@endif

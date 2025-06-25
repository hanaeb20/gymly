@extends('layouts.app')

@section('content')
<style>
    .title {
        color: white;
        font-size: 72px;
        font-family: 'Anta', sans-serif;
        font-weight: 400;
        margin: 0;
    }
    .subtitle {
        color: white;
        font-size: 28px;
        font-family: 'Antic', sans-serif;
        font-weight: 400;
    }
    .cta-button {
        color: white;
        font-size: 32px;
        font-family: 'Anta', sans-serif;
        font-weight: 400;
        text-decoration: none;
    }
    #results-container {
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    #results-container.loading {
        opacity: 0.5;
    }
    .loading-spinner {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
    }
    .loading-spinner.visible {
        display: block;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .rectangle {
  position: absolute;
  width: 1623px;
  height: 974px;
  top: 0;
  left: 0;
}
</style>

<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

<!-- Section Hero -->
<div style="width: 100%; height: 100vh; position: relative;">
    <!-- Fond rose -->
    <div style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; background-color: #DE2A80; z-index: -2;"></div>

    <!-- Contenu principal -->
    <div style="position: relative; z-index: 1; padding-top: 160px; padding-left: 100px;">
        <h1 class="title">GYMLY</h1>
        <p class="subtitle" style="max-width: 600px; margin-top: 32px;">
            Rejoignez la communauté et dépassez vos limites
        </p>
        <a href="#search" class="cta-button" style="display: inline-block; margin-top: 40px; position: relative;">
            <svg width="360" height="80" viewBox="0 0 449 98" fill="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; z-index: -1;">
                <path d="M0.276387 39.9995C0.386671 17.9084 18.3844 0.101825 40.4754 0.227432L408.226 2.31843C430.317 2.44403 448.136 20.4542 448.026 42.5454L447.949 57.8986C447.839 79.9898 429.841 97.7963 407.75 97.6707L39.9994 95.5797C17.9083 95.4541 0.0894561 77.4439 0.19974 55.3528L0.276387 39.9995Z" fill="#2B2051"/>
            </svg>
            <span style="position: relative; z-index: 1; padding: 20px 60px; display: inline-block;">En savoir plus</span>
        </a>
    </div>



    <!-- Image du boxeur -->
    <img style="width: 1500px; height: auto; position: absolute; right: 0; top: 100px; z-index: -1;" src="{{ asset('images/box.png') }}" alt="Boxer" />
     <!-- Vague violette -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: auto; z-index: -1;">
        <svg width="100%" height="auto" viewBox="0 0 1619 872" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 871C1289.93 863.629 1616.14 287.262 1618 0V871H0Z" fill="#2B2051"/>
        </svg>
    </div>


</div>

<!-- Section Recherche -->
<div style="background-color: #2B2051; padding: 80px 0;">
    <!-- Barre de recherche -->
    <div id="search" style="position: relative; z-index: 2; padding: 32px; background: white; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.1); max-width: 1000px; margin-left: auto; margin-right: auto;">
        <form id="search-form" style="display: flex; gap: 16px; align-items: flex-end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 8px; color: #2B2051; font-family: 'Anton', sans-serif;">Ville</label>
                <input type="text" name="ville" id="ville" placeholder="Entrez une ville"
                       style="width: 100%; padding: 12px; border: 2px solid #2B2051; border-radius: 8px; font-family: 'Antic', sans-serif; font-size: 16px;">
            </div>

            <button type="submit" style="background: #DE2A80; color: white; border: none; padding: 12px 32px; border-radius: 8px; font-family: 'Anta', sans-serif; font-size: 18px; cursor: pointer; transition: background 0.3s;">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Indicateur de chargement -->
    <div class="loading-spinner">
        <div style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #DE2A80; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    </div>

    <!-- Liste des salles -->
    <div id="results-container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <div id="salles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            @include('partials.salles-list', ['salles' => $salles ?? collect([])])
        </div>

        <!-- Pagination -->
        @if(isset($salles) && $salles->hasPages())
        <div style="display: flex; justify-content: center; margin-top: 40px;">
            {{ $salles->links() }}
        </div>
        @endif
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Footer Section -->
<footer style="background-color: #2B2051; color: white; padding: 60px 0; margin-top: 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
            <!-- About Section -->
            <div>
                <h3 style="color: #DE2A80; font-family: 'Anta', sans-serif; font-size: 24px; margin-bottom: 20px;">GYMLY</h3>
                <p style="font-family: 'Antic', sans-serif; line-height: 1.6;">
                    La plateforme qui connecte les passionnés de sport avec les meilleures salles de sport et coachs.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 style="color: #DE2A80; font-family: 'Anta', sans-serif; font-size: 24px; margin-bottom: 20px;">Liens rapides</h3>
                <ul style="list-style: none; padding: 0; font-family: 'Antic', sans-serif;">
                    <li style="margin-bottom: 10px;"><a href="/" style="color: white; text-decoration: none;">Accueil</a></li>
                    <li style="margin-bottom: 10px;"><a href="/salles" style="color: white; text-decoration: none;">Salles de sport</a></li>
                    <li style="margin-bottom: 10px;"><a href="/coachs" style="color: white; text-decoration: none;">Coachs</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 style="color: #DE2A80; font-family: 'Anta', sans-serif; font-size: 24px; margin-bottom: 20px;">Contactez-nous</h3>
                <ul style="list-style: none; padding: 0; font-family: 'Antic', sans-serif;">
                    <li style="margin-bottom: 10px;">contact@gymly.com</li>
                    <li style="margin-bottom: 10px;">+212 6 12 34 56 78</li>
                    <li style="margin-bottom: 10px;">Casablanca, Maroc</li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 40px; padding-top: 20px; text-align: center; font-family: 'Antic', sans-serif;">
            <p>&copy; {{ date('Y') }} GYMLY. Tous droits réservés.</p>
        </div>
    </div>
</footer>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            performSearch();
        });

        // Recherche en temps réel
        let timeout = null;
        $('#sport, #ville, #public').on('change keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(performSearch, 500);
        });

        function performSearch() {
            const resultsContainer = $('#results-container');
            const loadingSpinner = $('.loading-spinner');
            const searchData = {
                sport: $('#sport').val(),
                ville: $('#ville').val(),
                public: $('#public').val()
            };

            // Afficher le spinner et réduire l'opacité des résultats
            loadingSpinner.addClass('visible');
            resultsContainer.addClass('loading');

            // Mettre à jour l'URL avec les paramètres de recherche
            const searchParams = new URLSearchParams(searchData);
            window.history.replaceState({}, '', `${window.location.pathname}?${searchParams}`);

            $.ajax({
                url: '{{ route('salles.search') }}',
                method: 'GET',
                data: searchData,
                success: function(response) {
                    $('#salles-grid').html(response);
                },
                error: function(xhr) {
                    let errorMessage = 'Une erreur est survenue lors de la recherche.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $('#salles-grid').html(`<div class="col-span-full text-center text-red-600">${errorMessage}</div>`);
                },
                complete: function() {
                    loadingSpinner.removeClass('visible');
                    resultsContainer.removeClass('loading');
                }
            });
        }

        // Charger les paramètres de recherche depuis l'URL au chargement
        const urlParams = new URLSearchParams(window.location.search);
        $('#sport').val(urlParams.get('sport') || '');
        $('#ville').val(urlParams.get('ville') || '');
        $('#public').val(urlParams.get('public') || '');
        if (urlParams.has('sport') || urlParams.has('ville') || urlParams.has('public')) {
            performSearch();
        }
    });

</script>
@endpush
@endsection


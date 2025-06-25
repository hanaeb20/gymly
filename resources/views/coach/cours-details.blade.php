@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#DE2A80] to-[#2B2051]">
    <!-- Navigation -->
    <nav class="bg-white bg-opacity-10 backdrop-blur-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12" src="/images/logo.png" alt="Gymly">
                    </div>
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('salle.index') }}" class="text-white hover:text-[#DE2A80] px-3 py-2 rounded-md text-sm font-medium">Salle de sport</a>
                        <a href="{{ route('cours.index') }}" class="text-white hover:text-[#DE2A80] px-3 py-2 rounded-md text-sm font-medium">Cours</a>
                        <a href="#" class="text-white hover:text-[#DE2A80] px-3 py-2 rounded-md text-sm font-medium">Mon profile</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-[#DE2A80] px-3 py-2 rounded-md text-sm font-medium">Se déconnecter</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Espace coach</h1>
            <h2 class="text-2xl font-semibold text-white mb-8">Participants du {{ \Carbon\Carbon::parse($cours->date)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }}</h2>
        </div>

        <!-- Tableau des participants -->
        <div class="bg-white bg-opacity-10 backdrop-blur-xl rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white border-opacity-20">
                        <th class="px-6 py-4 text-left text-white">Jour de la seance</th>
                        <th class="px-6 py-4 text-left text-white">Heure de la seance</th>
                        <th class="px-6 py-4 text-left text-white">Liste des participants</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-white border-opacity-10">
                        <td class="px-6 py-4 text-white">{{ \App\Http\Controllers\CoursController::convertirJourEnFrancais(\Carbon\Carbon::parse($cours->date)->format('l')) }} {{ \Carbon\Carbon::parse($cours->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }}-{{ \Carbon\Carbon::parse($cours->heure_fin)->format('H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                @foreach($participants as $participant)
                                <div class="flex items-center justify-between text-white">
                                    <span>{{ $participant->name }}</span>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 bg-green-500 text-white text-sm rounded-full hover:bg-green-600">Confirmé</button>
                                        <button class="px-3 py-1 bg-red-500 text-white text-sm rounded-full hover:bg-red-600">Non confirmé</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bouton retour -->
        <div class="mt-8 text-center">
            <a href="{{ route('coach.dashboard') }}"
               class="inline-block px-6 py-3 bg-[#DE2A80] text-white font-semibold rounded-xl hover:bg-opacity-90 transition-all duration-200">
                Retour à mes cours
            </a>
        </div>
    </div>
</div>

<style>
    /* Animation des bulles en arrière-plan */
    .bubble {
        position: absolute;
        background: radial-gradient(circle at center, rgba(222, 42, 128, 0.3) 0%, rgba(43, 32, 81, 0.3) 100%);
        border-radius: 50%;
        animation: float 8s infinite ease-in-out;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-20px) scale(1.1); }
    }
</style>

<script>
    // Création des bulles en arrière-plan
    function createBubbles() {
        const container = document.querySelector('.min-h-screen');
        for (let i = 0; i < 5; i++) {
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.style.width = Math.random() * 200 + 100 + 'px';
            bubble.style.height = bubble.style.width;
            bubble.style.left = Math.random() * 100 + '%';
            bubble.style.top = Math.random() * 100 + '%';
            bubble.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(bubble);
        }
    }
    createBubbles();
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Modifier le cours</h1>

        <form action="{{ route('gerant.cours.update', $cours) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom du cours</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $cours->nom) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="coach_id" class="block text-sm font-medium text-gray-700">Coach</label>
                <select name="coach_id" id="coach_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Sélectionnez un coach</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}" {{ old('coach_id', $cours->coach_id) == $coach->id ? 'selected' : '' }}>
                            {{ $coach->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="jour" class="block text-sm font-medium text-gray-700">Jour</label>
                <select name="jour" id="jour" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Sélectionnez un jour</option>
                    <option value="lundi" {{ old('jour', $cours->jour) == 'lundi' ? 'selected' : '' }}>Lundi</option>
                    <option value="mardi" {{ old('jour', $cours->jour) == 'mardi' ? 'selected' : '' }}>Mardi</option>
                    <option value="mercredi" {{ old('jour', $cours->jour) == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                    <option value="jeudi" {{ old('jour', $cours->jour) == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                    <option value="vendredi" {{ old('jour', $cours->jour) == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                    <option value="samedi" {{ old('jour', $cours->jour) == 'samedi' ? 'selected' : '' }}>Samedi</option>
                    <option value="dimanche" {{ old('jour', $cours->jour) == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
                </select>
            </div>

            <div>
                <label for="heure_debut" class="block text-sm font-medium text-gray-700">Heure de début</label>
                <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', $cours->heure_debut) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="duree" class="block text-sm font-medium text-gray-700">Durée (en minutes)</label>
                <input type="number" name="duree" id="duree" min="30" max="180" value="{{ old('duree', $cours->duree) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité maximale</label>
                <input type="number" name="capacite" id="capacite" min="1" value="{{ old('capacite', $cours->capacite) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $cours->description) }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('gerant.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

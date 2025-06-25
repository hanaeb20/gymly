@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête du profil -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="relative h-48 bg-gradient-to-r from-blue-600 to-blue-800">
                    <div class="absolute -bottom-16 left-8">
                        <div class="w-32 h-32 rounded-full bg-white p-1 shadow-lg">
                            <div class="w-full h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center">
                                <span class="text-white text-4xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-20 px-8 pb-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $user->name }}</h1>
                            <div class="flex items-center space-x-6">
                                <p class="text-gray-600 flex items-center">
                                    <i class="fas fa-envelope text-blue-600 mr-2"></i>{{ $user->email }}
                                </p>
                                @if($user->telephone)
                                <p class="text-gray-600 flex items-center">
                                    <i class="fas fa-phone text-blue-600 mr-2"></i>{{ $user->telephone }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <a href="#" class="btn-edit">
                            <i class="fas fa-camera mr-2"></i>Changer la photo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulaire de modification -->
            <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier mes informations</h2>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label" for="name">Nom et prénom</label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name', $user->name) }}"
                                   class="form-input">
                    </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email', $user->email) }}"
                                   class="form-input">
                    </div>

                        <div class="form-group">
                            <label class="form-label" for="telephone">Téléphone</label>
                            <input type="tel" name="telephone" id="telephone"
                                   value="{{ old('telephone', $user->telephone) }}"
                                   class="form-input">
                    </div>

                        <div class="form-group">
                            <label class="form-label" for="current_password">Mot de passe actuel</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="form-input">
                    </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password"
                                   class="form-input">
                    </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-input">
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-edit {
        background: #f3f4f6;
        color: #374151;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .btn-edit:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .form-group {
        position: relative;
    }

    .form-label {
        display: block;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #374151;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        background: #ffffff;
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-submit {
        background: #3b82f6;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 0.5rem;
        color: #374151;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        font-size: 2rem;
        margin-right: 1rem;
    }

    .stat-title {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #111827;
    }
</style>
@endpush
@endsection

<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materiel;
use App\Models\Inscription;
use App\Models\Salle;
use App\Models\Cours;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Vérifier que l'utilisateur est bien un gérant
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salleId = Auth::user()->salle_id;

        // Vérifier que le gérant a une salle
        if (!$salleId) {
            return redirect()->route('salles.create')
                           ->with('error', 'Vous devez d\'abord créer une salle');
        }

        $salle = Salle::with(['photos', 'cours', 'annonces'])
                     ->where('gerant_id', Auth::id())
                     ->first();

        if (!$salle) {
            return redirect()->route('salles.create')
                           ->with('error', 'Vous devez d\'abord créer une salle');
        }

        // Statistiques
        $membresCount = User::where('salle_id', $salleId)
            ->where('role', 'client')
            ->count();

        $coachsCount = User::where('salle_id', $salleId)
            ->where('role', 'coach')
            ->count();

        $materielsCount = Materiel::where('salle_id', $salleId)->count();

        // Revenus du mois (à implémenter selon votre logique métier)
        $revenusMois = 0;

        // Dernières inscriptions
        $dernieresInscriptions = Inscription::where('salle_id', $salleId)
            ->where('statut', 'en_attente')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $cours = Cours::where('salle_id', $salle->id)
                     ->with('coach')
                     ->get();

        return view('gerant.dashboard', compact(
            'membresCount',
            'coachsCount',
            'materielsCount',
            'revenusMois',
            'dernieresInscriptions',
            'salle',
            'cours'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssaieController extends Controller
{
    public function index()
    {
        // Récupérer le coach connecté
        $coach = auth()->user();

        // Récupérer uniquement les réservations des cours du coach connecté
        $reservations = \App\Models\Reservation::with(['client', 'cours'])
            ->whereHas('cours', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->get();

        // Récupérer uniquement les cours du coach connecté
        $cours = \App\Models\Cours::with('coach')
            ->where('coach_id', $coach->id)
            ->get();

        // Filtrer les réservations pour ne garder que celles avec des clients et des cours valides
        $reservations = $reservations->filter(function($reservation) {
            return $reservation->client !== null && $reservation->cours !== null;
        });

        // Filtrer les cours pour ne garder que ceux avec des coachs valides
        $cours = $cours->filter(function($cour) {
            return $cour->coach !== null;
        });

        return view('essaie', compact('reservations', 'cours'));
    }

    public function simple()
    {
        return view('essaie.essaie');
    }
}

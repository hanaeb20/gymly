<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Abonnement;
use App\Models\Inscription;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        $salle = auth()->user()->salle;

        // Statistiques des membres
        $totalMembres = User::where('salle_id', $salle->id)
            ->where('role', 'client')
            ->count();

        // Statistiques des abonnements
        $abonnementsActifs = Abonnement::where('salle_id', $salle->id)
            ->where('statut', 'actif')
            ->count();

        $abonnementsExpires = Abonnement::where('salle_id', $salle->id)
            ->where('statut', 'expire')
            ->count();

        // Revenus du mois
        $revenusMois = Abonnement::where('salle_id', $salle->id)
            ->where('statut', 'actif')
            ->whereMonth('date_debut', Carbon::now()->month)
            ->sum('prix');

        // Inscriptions en attente
        $inscriptionsEnAttente = Inscription::where('salle_id', $salle->id)
            ->where('statut', 'en_attente')
            ->count();

        return view('gerant.statistiques.index', compact(
            'totalMembres',
            'abonnementsActifs',
            'abonnementsExpires',
            'revenusMois',
            'inscriptionsEnAttente'
        ));
    }
}

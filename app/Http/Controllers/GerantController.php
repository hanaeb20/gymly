<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horaire;
use App\Models\Salle;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GerantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le tableau de bord du gérant
     */
    public function dashboard()
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return redirect()->route('salles.create')->with('error', 'Vous devez d\'abord créer une salle');
        }

        // Récupération des statistiques
        $membresCount = User::where('salle_id', $salle->id)->count();
        $equipementsCount = $salle->equipements()->count();
        $abonnementsCount = $salle->abonnements()->where('date_fin', '>', now())->count();

        // Calcul des revenus du mois
        $revenusMois = $salle->abonnements()
            ->whereMonth('created_at', now()->month)
            ->sum('prix');

        return view('gerant.dashboard', compact(
            'membresCount',
            'equipementsCount',
            'abonnementsCount',
            'revenusMois',
            'salle'
        ));
    }

    /**
     * Affiche la page de gestion des horaires
     */
    public function horaires()
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        // Récupérer la salle du gérant
        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return redirect()->route('salles.create')
                           ->with('error', 'Vous devez d\'abord créer une salle avant de gérer les horaires');
        }

        $horaires = Horaire::where('salle_id', $salle->id)
                          ->orderBy('jour')
                          ->orderBy('heure_ouverture')
                          ->get();

        return view('gerant.horaires', compact('horaires'));
    }

    /**
     * Enregistre un nouvel horaire
     */
    public function storeHoraire(Request $request)
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $validated = $request->validate([
            'jour' => 'required|string',
            'heure_ouverture' => 'required',
            'heure_fermeture' => 'required|after:heure_ouverture',
        ]);

        // Récupérer la salle du gérant
        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return back()->with('error', 'Vous devez d\'abord créer une salle');
        }

        // Ajouter le salle_id aux données validées
        $validated['salle_id'] = $salle->id;

        // Créer l'horaire
        Horaire::create($validated);

        return back()->with('success', 'Horaire ajouté avec succès');
    }

    /**
     * Met à jour un horaire existant
     */
    public function updateHoraire(Request $request, $id)
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return back()->with('error', 'Salle non trouvée');
        }

        $horaire = Horaire::where('salle_id', $salle->id)->findOrFail($id);

        $validated = $request->validate([
            'jour' => 'required|string',
            'heure_ouverture' => 'required',
            'heure_fermeture' => 'required|after:heure_ouverture',
        ]);

        $horaire->update($validated);

        return back()->with('success', 'Horaire mis à jour avec succès');
    }

    /**
     * Supprime un horaire
     */
    public function deleteHoraire($id)
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return back()->with('error', 'Salle non trouvée');
        }

        $horaire = Horaire::where('salle_id', $salle->id)->findOrFail($id);
        $horaire->delete();

        return back()->with('success', 'Horaire supprimé avec succès');
    }

    /**
     * Affiche la page de gestion des équipements
     */
    public function equipements()
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return redirect()->route('salles.create')
                           ->with('error', 'Vous devez d\'abord créer une salle');
        }

        $equipements = $salle->equipements()->orderBy('nom')->get();
        return view('gerant.equipements', compact('equipements'));
    }

    /**
     * Enregistre un nouvel équipement
     */
    public function storeEquipement(Request $request)
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
            'etat' => 'required|string|in:Neuf,Bon,Moyen,Mauvais,Hors service',
            'date_achat' => 'required|date',
        ]);

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return back()->with('error', 'Salle non trouvée');
        }

        $salle->equipements()->create($validated);

        return back()->with('success', 'Équipement ajouté avec succès');
    }

    /**
     * Met à jour un équipement existant
     */
    public function updateEquipement(Request $request, $id)
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return back()->with('error', 'Salle non trouvée');
        }

        $equipement = $salle->equipements()->findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
            'etat' => 'required|string|in:Neuf,Bon,Moyen,Mauvais,Hors service',
            'date_achat' => 'required|date',
        ]);

        $equipement->update($validated);

        return back()->with('success', 'Équipement mis à jour avec succès');
    }

    /**
     * Supprime un équipement
     */
    public function deleteEquipement($id)
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        $salle = Salle::where('gerant_id', Auth::id())->first();

        if (!$salle) {
            return back()->with('error', 'Salle non trouvée');
        }

        $equipement = $salle->equipements()->findOrFail($id);
        $equipement->delete();

        return back()->with('success', 'Équipement supprimé avec succès');
    }
}

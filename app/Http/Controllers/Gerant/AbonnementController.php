<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abonnement;

class AbonnementController extends Controller
{
    public function index()
    {
        $abonnements = Abonnement::where('salle_id', auth()->user()->salle->id)
            ->with('user')
            ->get();
        return view('gerant.abonnements.index', compact('abonnements'));
    }

    public function create()
    {
        return view('gerant.abonnements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,expire,annule',
        ]);

        $abonnement = new Abonnement($validated);
        $abonnement->salle_id = auth()->user()->salle->id;
        $abonnement->save();

        return redirect()->route('gerant.abonnements.index')
            ->with('success', 'Abonnement créé avec succès');
    }

    public function edit(Abonnement $abonnement)
    {
        return view('gerant.abonnements.edit', compact('abonnement'));
    }

    public function update(Request $request, Abonnement $abonnement)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,expire,annule',
        ]);

        $abonnement->update($validated);

        return redirect()->route('gerant.abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès');
    }

    public function destroy(Abonnement $abonnement)
    {
        $abonnement->delete();
        return redirect()->route('gerant.abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès');
    }
}

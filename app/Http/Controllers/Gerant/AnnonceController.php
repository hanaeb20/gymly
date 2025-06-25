<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index()
    {
        $salle = Salle::where('gerant_id', Auth::id())->first();
        $annonces = $salle->annonces()->orderBy('created_at', 'desc')->get();
        return view('gerant.annonces.index', compact('annonces', 'salle'));
    }

    public function create()
    {
        $salle = Salle::where('gerant_id', Auth::id())->first();
        return view('gerant.annonces.create', compact('salle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $salle = Salle::where('gerant_id', Auth::id())->first();

        $annonce = new Annonce($validated);
        $annonce->salle_id = $salle->id;
        $annonce->save();

        return redirect()->route('gerant.annonces.index')
            ->with('success', 'Annonce créée avec succès');
    }

    public function edit(Annonce $annonce)
    {
        if ($annonce->salle->gerant_id !== Auth::id()) {
            abort(403);
        }
        return view('gerant.annonces.edit', compact('annonce'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        if ($annonce->salle->gerant_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'est_active' => 'boolean'
        ]);

        $annonce->update($validated);

        return redirect()->route('gerant.annonces.index')
            ->with('success', 'Annonce mise à jour avec succès');
    }

    public function destroy(Annonce $annonce)
    {
        if ($annonce->salle->gerant_id !== Auth::id()) {
            abort(403);
        }

        $annonce->delete();

        return redirect()->route('gerant.annonces.index')
            ->with('success', 'Annonce supprimée avec succès');
    }
}

<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipement;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::where('salle_id', auth()->user()->salle->id)->get();
        return view('gerant.equipements.index', compact('equipements'));
    }

    public function create()
    {
        return view('gerant.equipements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'quantite' => 'required|integer|min:0',
            'etat' => 'required|string',
        ]);

        $equipement = new Equipement($validated);
        $equipement->salle_id = auth()->user()->salle->id;
        $equipement->save();

        return redirect()->route('gerant.equipements.index')
            ->with('success', 'Équipement ajouté avec succès');
    }

    public function edit(Equipement $equipement)
    {
        return view('gerant.equipements.edit', compact('equipement'));
    }

    public function update(Request $request, Equipement $equipement)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'quantite' => 'required|integer|min:0',
            'etat' => 'required|string',
        ]);

        $equipement->update($validated);

        return redirect()->route('gerant.equipements.index')
            ->with('success', 'Équipement mis à jour avec succès');
    }

    public function destroy(Equipement $equipement)
    {
        $equipement->delete();
        return redirect()->route('gerant.equipements.index')
            ->with('success', 'Équipement supprimé avec succès');
    }
}

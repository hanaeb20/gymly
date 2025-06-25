<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiel;
use Illuminate\Support\Facades\Auth;

class MaterielController extends Controller
{
    public function index()
    {
        $materiels = Materiel::where('salle_id', Auth::user()->salle_id)->get();
        return view('gerant.materiel.index', compact('materiels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
        ]);

        $materiel = new Materiel($validated);
        $materiel->salle_id = Auth::user()->salle_id;
        $materiel->save();

        return redirect()->route('gerant.materiel.index')
            ->with('success', 'Matériel ajouté avec succès');
    }

    public function update(Request $request, Materiel $materiel)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
        ]);

        $materiel->update($validated);

        return redirect()->route('gerant.materiel.index')
            ->with('success', 'Matériel mis à jour avec succès');
    }

    public function destroy(Materiel $materiel)
    {
        $materiel->delete();
        return redirect()->route('gerant.materiel.index')
            ->with('success', 'Matériel supprimé avec succès');
    }
}

<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Horaire;
use Illuminate\Support\Facades\Auth;

class HoraireController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        if (!Auth::user()->isGerant()) {
            return redirect()->back()
                ->with('error', 'Seuls les gérants peuvent gérer les horaires.');
        }

        if (!Auth::user()->salle) {
            return redirect()->route('gerant.salles.create')
                ->with('error', 'Vous devez d\'abord créer une salle avant de pouvoir gérer les horaires.');
        }

        $horaires = Horaire::where('salle_id', Auth::user()->salle->id)->get();
        return view('gerant.horaires.index', compact('horaires'));
    }

    public function create()
    {
        return view('gerant.horaires.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        if (!Auth::user()->isGerant()) {
            return redirect()->back()
                ->with('error', 'Seuls les gérants peuvent gérer les horaires.');
        }

        if (!Auth::user()->salle) {
            return redirect()->route('gerant.salles.create')
                ->with('error', 'Vous devez d\'abord créer une salle avant de pouvoir gérer les horaires.');
        }

        $validated = $request->validate([
            'jour' => 'required|string',
            'heure_ouverture' => 'required|date_format:H:i',
            'heure_fermeture' => 'required|date_format:H:i|after:heure_ouverture',
        ]);

        $horaire = new Horaire($validated);
        $horaire->salle_id = Auth::user()->salle->id;
        $horaire->save();

        return redirect()->route('gerant.horaires.index')
            ->with('success', 'Horaire ajouté avec succès');
    }

    public function edit(Horaire $horaire)
    {
        return view('gerant.horaires.edit', compact('horaire'));
    }

    public function update(Request $request, Horaire $horaire)
    {
        $validated = $request->validate([
            'jour' => 'required|string',
            'heure_ouverture' => 'required|date_format:H:i',
            'heure_fermeture' => 'required|date_format:H:i|after:heure_ouverture',
        ]);

        $horaire->update($validated);

        return redirect()->route('gerant.horaires.index')
            ->with('success', 'Horaire mis à jour avec succès');
    }

    public function destroy(Horaire $horaire)
    {
        $horaire->delete();
        return redirect()->route('gerant.horaires.index')
            ->with('success', 'Horaire supprimé avec succès');
    }
}

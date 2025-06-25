<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursController extends Controller
{
    public function index(Request $request)
    {
        $query = Cours::query();

        // Filtres
        if ($request->has('type_cours') && $request->type_cours) {
            $query->where('type_cours', $request->type_cours);
        }

        if ($request->has('jour') && $request->jour) {
            $query->where('jour', $request->jour);
        }

        if ($request->has('coach_id') && $request->coach_id) {
            $query->where('coach_id', $request->coach_id);
        }

        // Récupération des données
        $cours = $query->with(['coach', 'inscriptions'])->paginate(10);
        $types_cours = Cours::pluck('type_cours')->unique();
        $coaches = User::where('role', 'coach')->get();

        return view('gerant.cours.index', compact('cours', 'types_cours', 'coaches'));
    }

    public function edit(Cours $cours)
    {
        $salle = Salle::where('gerant_id', Auth::id())->firstOrFail();
        if ($cours->salle_id !== $salle->id) {
            abort(403);
        }

        $coaches = User::where('role', 'coach')
                      ->where('salle_id', $salle->id)
                      ->get();

        return view('gerant.cours.edit', compact('cours', 'coaches'));
    }

    public function update(Request $request, Cours $cours)
    {
        $salle = Salle::where('gerant_id', Auth::id())->firstOrFail();
        if ($cours->salle_id !== $salle->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'coach_id' => 'required|exists:coaches,id',
            'jour' => 'required|string|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'duree' => 'required|integer|min:30|max:180',
            'capacite' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $cours->update($validated);

        return redirect()->route('gerant.dashboard')
            ->with('success', 'Le cours a été mis à jour avec succès.');
    }

    public function destroy(Cours $cours)
    {
        $salle = Salle::where('gerant_id', Auth::id())->firstOrFail();
        if ($cours->salle_id !== $salle->id) {
            abort(403);
        }

        $cours->delete();

        return redirect()->route('gerant.dashboard')
            ->with('success', 'Le cours a été supprimé avec succès.');
    }
}

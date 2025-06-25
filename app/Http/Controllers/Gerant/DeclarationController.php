<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Declaration;
use Illuminate\Support\Facades\Auth;

class DeclarationController extends Controller
{
    public function index()
    {
        $declarations = Declaration::whereHas('salle', function($query) {
            $query->where('gerant_id', Auth::id());
        })
        ->with(['user', 'salle'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('gerant.declarations.index', compact('declarations'));
    }

    public function show(Declaration $declaration)
    {
        if ($declaration->salle->gerant_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $declaration->update(['statut' => 'lu']);
        return view('gerant.declarations.show', compact('declaration'));
    }

    public function update(Request $request, Declaration $declaration)
    {
        if ($declaration->salle->gerant_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'statut' => 'required|in:en_attente,lu,repondu'
        ]);

        $declaration->update([
            'statut' => $request->statut
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeclarationController extends Controller
{
    public function index()
    {
        return redirect()->route('declarations.create');
    }

    public function create()
    {
        $salles = Salle::all();
        return view('declarations.create', compact('salles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $declaration = new Declaration();
        $declaration->user_id = Auth::id();
        $declaration->salle_id = $validated['salle_id'];
        $declaration->sujet = $validated['sujet'];
        $declaration->message = $validated['message'];
        $declaration->statut = 'en_attente';
        $declaration->save();

        return redirect()->route('declarations.create')
            ->with('success', 'Votre déclaration a été envoyée avec succès.');
    }
}

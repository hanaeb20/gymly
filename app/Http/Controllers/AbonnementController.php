<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function show()
    {
        $abonnement = Abonnement::where('user_id', auth()->id())->first();
        return view('abonnements.show', compact('abonnement'));
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:mensuel,trimestriel,annuel',
            'salle_id' => 'required|exists:salles,id',
        ]);

        $abonnement = Abonnement::create([
            'user_id' => auth()->id(),
            'salle_id' => $validated['salle_id'],
            'type' => $validated['type'],
            'date_debut' => now(),
            'date_fin' => $this->calculateEndDate($validated['type']),
        ]);

        return redirect()->route('abonnements.show')->with('success', 'Abonnement souscrit avec succès');
    }

    private function calculateEndDate($type)
    {
        return match($type) {
            'mensuel' => now()->addMonth(),
            'trimestriel' => now()->addMonths(3),
            'annuel' => now()->addYear(),
        };
    }
}

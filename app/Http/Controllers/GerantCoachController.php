<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GerantCoachController extends Controller
{
    public function index()
    {
        $coachs = auth()->user()->coachs;
        return view('gerant.coachs.index', compact('coachs'));
    }

    public function create()
    {
        return view('gerant.coachs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:20',
            'specialite' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
        ]);

        $coach = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'telephone' => $validated['telephone'],
            'specialite' => $validated['specialite'],
            'experience' => $validated['experience'],
            'role' => 'coach',
            'gerant_id' => auth()->id(),
        ]);

        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Coach ajouté avec succès');
    }

    public function edit(User $coach)
    {
        if ($coach->gerant_id !== auth()->id()) {
            abort(403);
        }
        return view('gerant.coachs.edit', compact('coach'));
    }

    public function update(Request $request, User $coach)
    {
        if ($coach->gerant_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $coach->id,
            'telephone' => 'required|string|max:20',
            'specialite' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
        ]);

        $coach->update($validated);

        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Coach mis à jour avec succès');
    }

    public function destroy(User $coach)
    {
        if ($coach->gerant_id !== auth()->id()) {
            abort(403);
        }

        $coach->delete();

        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Coach supprimé avec succès');
    }
}

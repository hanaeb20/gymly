<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }

    public function index()
    {
        $salles = Salle::with('photos')->paginate(9);
        \Log::info('Salles chargées avec photos:', ['salles' => $salles->toArray()]);
        return view('salles.index', compact('salles'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'gerant') {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        return view('salles.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'gerant') {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'prix_abonnement' => 'required|numeric|min:0',
        ]);

        // Créer un tableau avec toutes les données nécessaires
        $salleData = [
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'adresse' => $validated['adresse'],
            'ville' => $validated['ville'],
            'code_postal' => $validated['code_postal'],
            'telephone' => $validated['telephone'],
            'email' => $validated['email'],
            'prix_abonnement' => $validated['prix_abonnement'],
            'gerant_id' => Auth::id(),
            'est_active' => true,
            'horaires_ouverture' => json_encode([
                'lundi' => ['08:00', '20:00'],
                'mardi' => ['08:00', '20:00'],
                'mercredi' => ['08:00', '20:00'],
                'jeudi' => ['08:00', '20:00'],
                'vendredi' => ['08:00', '20:00'],
                'samedi' => ['09:00', '18:00'],
                'dimanche' => ['09:00', '18:00']
            ]),
            'equipements' => json_encode([])
        ];

        $salle = Salle::create($salleData);

        return redirect()->route('gerant.horaires')
                        ->with('success', 'Salle créée avec succès');
    }

    public function show(Salle $salle)
    {
        return view('salles.show', compact('salle'));
    }

    public function edit(Salle $salle)
    {
        if (Auth::user()->role !== 'gerant' || $salle->gerant_id !== Auth::id()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        return view('salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        if (Auth::user()->role !== 'gerant' || $salle->gerant_id !== Auth::id()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $salle->update($validated);

        return redirect()->route('gerant.horaires')
                        ->with('success', 'Salle mise à jour avec succès');
    }

    public function destroy(Salle $salle)
    {
        if (Auth::user()->role !== 'gerant' || $salle->gerant_id !== Auth::id()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        $salle->delete();
        return redirect()->route('salles.index')
                        ->with('success', 'Salle supprimée avec succès');
    }

    public function inscription(Salle $salle)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'Vous devez être connecté pour vous inscrire');
        }

        if ($user->salle_id) {
            return back()->with('error', 'Vous êtes déjà inscrit dans une salle');
        }

        $user->update(['salle_id' => $salle->id]);

        return back()->with('success', 'Inscription réussie !');
    }

    public function annulerInscription(Salle $salle)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'Vous devez être connecté pour annuler votre inscription');
        }

        if ($user->salle_id !== $salle->id) {
            return back()->with('error', 'Vous n\'êtes pas inscrit dans cette salle');
        }

        $user->update(['salle_id' => null]);

        return back()->with('success', 'Inscription annulée avec succès !');
    }

    public function search(Request $request)
    {
        try {
            \Log::info('Début de la recherche avec les paramètres:', $request->all());

            $query = Salle::query();

            if ($request->filled('ville')) {
                $query->where('ville', 'like', '%' . $request->ville . '%');
            }

            $salles = $query->with(['equipements', 'photos'])->paginate(9);

            \Log::info('Nombre de salles trouvées: ' . $salles->count());

            if ($request->ajax()) {
                return view('partials.salles-list', compact('salles'))->render();
            }

            return view('home', compact('salles'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la recherche: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json(['error' => 'Une erreur est survenue lors de la recherche: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Une erreur est survenue lors de la recherche.');
        }
    }
}

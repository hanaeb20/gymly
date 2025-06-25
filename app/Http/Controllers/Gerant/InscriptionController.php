<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer la salle du gérant
        $salle = auth()->user()->salle;

        Log::info('Gérant connecté:', [
            'gerant_id' => auth()->id(),
            'salle_id' => $salle ? $salle->id : null
        ]);

        if (!$salle) {
            return redirect()->route('gerant.dashboard')
                ->with('error', 'Vous devez d\'abord créer une salle');
        }

        // Récupérer les utilisateurs inscrits dans la salle
        $query = User::where('salle_id', $salle->id)
                    ->where('role', 'client');

        // Log de la requête SQL
        Log::info('Requête SQL:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_debut));
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_fin));
        }

        $inscriptions = $query->latest()->paginate(10);

        // Log des résultats
        Log::info('Résultats de la requête:', [
            'nombre_utilisateurs' => $inscriptions->count(),
            'utilisateurs' => $inscriptions->toArray()
        ]);

        // Vérifier tous les utilisateurs de la salle
        $tousLesUtilisateurs = User::where('salle_id', $salle->id)->get();
        Log::info('Tous les utilisateurs de la salle:', [
            'nombre_total' => $tousLesUtilisateurs->count(),
            'utilisateurs' => $tousLesUtilisateurs->toArray()
        ]);

        return view('gerant.inscriptions.index', compact('inscriptions'));
    }

    public function create()
    {
        return view('gerant.inscriptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'required|string',
            'adresse' => 'required|string',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'client',
        ]);

        // Créer l'inscription
        Inscription::create([
            'user_id' => $user->id,
            'salle_id' => auth()->user()->salle->id,
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'],
            'statut' => 'en_attente',
        ]);

        return redirect()->route('gerant.inscriptions.index')
            ->with('success', 'Inscription créée avec succès');
    }

    public function show($id)
    {
        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Vérifier que l'utilisateur appartient à la salle du gérant
        if ($user->salle_id !== auth()->user()->salle_id) {
            abort(403, 'Accès non autorisé');
        }

        // Log pour le débogage
        Log::info('Détails de l\'utilisateur:', [
            'user' => $user->toArray()
        ]);

        return view('gerant.inscriptions.show', compact('user'));
    }

    public function update(Request $request, Inscription $inscription)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,approuvee,refusee',
        ]);

        $inscription->update($validated);

        return redirect()->route('gerant.inscriptions.index')
            ->with('success', 'Statut de l\'inscription mis à jour');
    }

    public function destroy(Inscription $inscription)
    {
        $inscription->delete();
        return redirect()->route('gerant.inscriptions.index')
            ->with('success', 'Inscription supprimée avec succès');
    }

    public function valider(User $inscription)
    {
        $this->authorize('update', $inscription);

        $inscription->update(['statut' => 'approuvee']);

        return redirect()->route('gerant.inscriptions.index')
            ->with('success', 'L\'inscription a été validée avec succès.');
    }

    public function refuser($id)
    {
        $user = User::findOrFail($id);

        // Vérifier que l'utilisateur appartient à la salle du gérant
        if ($user->salle_id !== auth()->user()->salle_id) {
            abort(403, 'Accès non autorisé');
        }

        // Mettre à jour l'utilisateur
        $user->update([
            'salle_id' => null,
            'statut' => 'refusee'
        ]);

        return redirect()->route('gerant.inscriptions.index')
            ->with('success', 'Le membre a été désinscrit avec succès.');
    }
}

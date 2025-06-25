<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur GerantCoachController
 *
 * Ce contrôleur gère toutes les actions liées à la gestion des coachs par les gérants.
 * Il permet aux gérants de gérer les coachs de leur salle.
 *
 * Fonctionnalités principales :
 * - Liste des coachs de la salle
 * - Création de nouveaux coachs
 * - Modification des informations des coachs
 * - Gestion des codes d'inscription
 * - Suppression des coachs
 */
class CoachController extends Controller
{
    /**
     * Affiche la liste des coachs de la salle du gérant
     * Génère automatiquement des codes d'inscription pour les nouveaux coachs
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer la salle du gérant connecté
        $salleId = Auth::user()->salle_id;

        if (!$salleId) {
            return redirect()->route('gerant.dashboard')
                ->with('error', 'Vous devez d\'abord créer une salle');
        }

        // Récupérer uniquement les coachs de la salle du gérant
        $coachs = User::where('role', 'coach')
            ->where('salle_id', $salleId)
            ->get();

        // Générer un code d'inscription pour les coachs qui n'en ont pas
        foreach ($coachs as $coach) {
            if (!$coach->inscription_code) {
                $coach->inscription_code = strtoupper(Str::random(8));
                $coach->save();
            }
        }

        return view('gerant.coachs.index', compact('coachs'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau coach
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('gerant.coachs.create');
    }

    /**
     * Enregistre un nouveau coach dans la base de données
     * Génère un code d'inscription unique
     *
     * @param Request $request Les données du formulaire
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'specialite' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'photo' => 'nullable'
        ]);

        // Vérifier que le gérant a une salle
        $salleId = Auth::user()->salle_id;
        if (!$salleId) {
            return redirect()->route('gerant.dashboard')
                ->with('error', 'Vous devez d\'abord créer une salle');
        }

        // Générer un code d'inscription unique
        $inscriptionCode = strtoupper(Str::random(8));

        // Créer le coach
        $coach = new User($validated);
        $coach->role = 'coach';
        $coach->salle_id = $salleId;
        $coach->inscription_code = $inscriptionCode;
        $coach->password = ''; // Le mot de passe sera défini lors de l'inscription

        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('coachs/photos', 'public');
            $coach->photo_profil = $path;
        }

        $coach->save();

        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Coach ajouté avec succès. Code d\'inscription : ' . $inscriptionCode);
    }

    /**
     * Affiche le formulaire de modification d'un coach
     *
     * @param User $coach Le coach à modifier
     * @return \Illuminate\View\View
     */
    public function edit(User $coach)
    {
        // Vérifier que le coach appartient à la salle du gérant
        if ($coach->salle_id !== Auth::user()->salle_id) {
            abort(403, 'Accès non autorisé');
        }

        return view('gerant.coachs.edit', compact('coach'));
    }

    public function update(Request $request, User $coach)
    {
        // Vérifier que le coach appartient à la salle du gérant
        if ($coach->salle_id !== Auth::user()->salle_id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $coach->id,
            'telephone' => 'required|string|max:20',
            'specialite' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'photo' => 'nullable'
        ]);

        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($coach->photo_profil) {
                Storage::disk('public')->delete($coach->photo_profil);
            }
            $path = $request->file('photo')->store('coachs/photos', 'public');
            $coach->photo_profil = $path;
        }

        $coach->update($validated);

        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Coach mis à jour avec succès');
    }

    public function destroy(User $coach)
    {
        // Vérifier que le coach appartient à la salle du gérant
        if ($coach->salle_id !== Auth::user()->salle_id) {
            abort(403, 'Accès non autorisé');
        }

        $coach->delete();
        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Coach supprimé avec succès');
    }

    /**
     * Régénère un nouveau code d'inscription pour un coach
     *
     * @param User $coach Le coach concerné
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateCode(User $coach)
    {
        // Vérifier que le coach appartient à la salle du gérant
        if ($coach->salle_id !== Auth::user()->salle_id) {
            abort(403, 'Accès non autorisé');
        }

        // Générer un nouveau code d'inscription
        $newCode = strtoupper(Str::random(8));
        $coach->inscription_code = $newCode;
        $coach->save();

        return redirect()->route('gerant.coachs.index')
            ->with('success', 'Nouveau code d\'inscription généré : ' . $newCode);
    }
}

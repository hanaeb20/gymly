<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salle;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SalleController extends Controller
{
    public function index()
    {
        $salle = Salle::where('gerant_id', Auth::id())->first();
        return view('gerant.salles.index', compact('salle'));
    }

    public function create()
    {
        return view('gerant.salles.create');
    }

    public function store(Request $request)
    {
        try {
            // Vérification de l'authentification
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une salle.');
            }

            // Vérification du rôle
            if (Auth::user()->role !== 'gerant') {
                return redirect()->back()->with('error', 'Seuls les gérants peuvent créer une salle.');
            }

            // Validation des données
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'ville' => 'required|string|max:255',
                'code_postal' => 'required|string|max:5',
                'telephone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'description' => 'nullable|string',
                'capacite' => 'required|integer|min:1',
                'prix_abonnement' => 'required|numeric|min:0',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Création de la salle avec les valeurs par défaut
            $salleData = array_merge($validated, [
                'gerant_id' => Auth::id(),
                'equipements' => json_encode([]),
                'horaires_ouverture' => json_encode([
                    'lundi' => ['08:00', '20:00'],
                    'mardi' => ['08:00', '20:00'],
                    'mercredi' => ['08:00', '20:00'],
                    'jeudi' => ['08:00', '20:00'],
                    'vendredi' => ['08:00', '20:00'],
                    'samedi' => ['09:00', '18:00'],
                    'dimanche' => ['09:00', '18:00']
                ]),
                'services' => json_encode([]),
                'avis' => json_encode([]),
                'note' => 0,
                'statut' => 'active',
                'est_active' => true,
                'prix_abonnement' => $validated['prix_abonnement']
            ]);

            $salle = Salle::create($salleData);

            // Association de la salle à l'utilisateur
            Auth::user()->update(['salle_id' => $salle->id]);

            // Gestion des photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('salles/photos', 'public');

                    $photoModel = new Photo();
                    $photoModel->chemin = $path;
                    $photoModel->salle_id = $salle->id;
                    $photoModel->save();
                }
            }

            return redirect()->route('gerant.horaires.index')
                ->with('success', 'Salle créée avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la salle : ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la salle : ' . $e->getMessage());
        }
    }

    public function update(Request $request, Salle $salle)
    {
        if ($salle->gerant_id !== Auth::id()) {
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
            'capacite' => 'required|integer|min:1',
            'prix_abonnement' => 'required|numeric|min:0',
        ]);

        $salle->update($validated);

        return redirect()->route('gerant.dashboard')
                        ->with('success', 'Salle mise à jour avec succès');
    }

    public function updateAnnonce(Request $request, Salle $salle)
    {
        if ($salle->gerant_id !== Auth::id()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        $validated = $request->validate([
            'annonce' => 'required|string|max:1000',
        ]);

        $salle->update(['annonce' => $validated['annonce']]);

        return redirect()->route('gerant.dashboard')
                        ->with('success', 'Annonce mise à jour avec succès');
    }

    public function addPhoto(Request $request, Salle $salle)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Vérifier que l'utilisateur est bien le gérant de cette salle
        if (Auth::user()->salle_id !== $salle->id) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }

        try {
            // Stocker la photo dans le dossier public
            $path = $request->file('photo')->store('salles/photos', 'public');
            Log::info('Photo stockée avec le chemin : ' . $path);

            // Vérifier si le fichier existe
            if (Storage::disk('public')->exists($path)) {
                Log::info('Le fichier existe bien dans le stockage');
            } else {
                Log::error('Le fichier n\'existe pas dans le stockage : ' . $path);
            }

            // Créer l'entrée dans la base de données
            $photo = $salle->photos()->create([
                'chemin' => $path,
            ]);
            Log::info('Photo enregistrée dans la base de données avec l\'ID : ' . $photo->id);

            return redirect()->back()->with('success', 'La photo a été ajoutée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout de la photo : ' . $e->getMessage());
            Log::error('Stack trace : ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout de la photo');
        }
    }

    public function deletePhoto(Salle $salle, Photo $photo)
    {
        // Vérifier que l'utilisateur est bien le gérant de cette salle
        if (Auth::user()->salle_id !== $salle->id) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }

        // Supprimer le fichier physique
        Storage::disk('public')->delete($photo->chemin);

        // Supprimer l'entrée dans la base de données
        $photo->delete();

        return redirect()->back()->with('success', 'La photo a été supprimée avec succès');
    }

    public function destroy(Salle $salle)
    {
        // Vérifier que l'utilisateur est bien le gérant de cette salle
        if ($salle->gerant_id !== Auth::id()) {
            abort(403, 'Cette action n\'est pas autorisée.');
        }

        // Supprimer toutes les photos associées
        foreach ($salle->photos as $photo) {
            Storage::disk('public')->delete($photo->chemin);
            $photo->delete();
        }

        // Supprimer la salle
        $salle->delete();

        return redirect()->route('salles.index')
                        ->with('success', 'Salle supprimée avec succès');
    }
}

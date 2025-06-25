<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cours::query();

        // Filtres
        if ($request->has('type_cours') && $request->type_cours) {
            $query->where('type_cours', $request->type_cours);
        }

        if ($request->has('jour') && $request->jour) {
            $jour = strtolower($request->jour);
            // Convertir le jour en anglais pour la recherche dans la base de données
            $jours = [
                'lundi' => 'monday',
                'mardi' => 'tuesday',
                'mercredi' => 'wednesday',
                'jeudi' => 'thursday',
                'vendredi' => 'friday',
                'samedi' => 'saturday',
                'dimanche' => 'sunday'
            ];
            $jourEnAnglais = $jours[$jour] ?? $jour;
            $query->where('jour', $jourEnAnglais);
        }

        if ($request->has('coach_id') && $request->coach_id) {
            $query->where('coach_id', $request->coach_id);
        }

        // Récupération des données
        $cours = $query->with(['coach', 'inscriptions'])->paginate(9);
        $types_cours = Cours::pluck('type_cours')->unique();
        $coaches = User::where('role', 'coach')->get();
        $salles = \App\Models\Salle::all();

        return view('cours.index', compact('cours', 'types_cours', 'coaches', 'salles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public static function convertirJourEnFrancais($jour)
    {
        $jours = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche'
        ];
        return $jours[strtolower($jour)] ?? $jour;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Données reçues:', $request->all());

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'coach_id' => 'required|exists:users,id',
                'capacite' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'type_cours' => 'required|string',
                'duree_minutes' => 'required|integer|min:30|max:180',
                'niveau' => 'required|string',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',
                'salle_id' => 'required|exists:salles,id',
                'date' => 'required|date'
            ]);

            // Préparation des données pour la création
            $data = $validated;
            $data['horaire_debut'] = $validated['heure_debut'];
            $data['horaire_fin'] = $validated['heure_fin'];
            $data['capacite_max'] = $validated['capacite'];
            // Stocker le jour en anglais
            $data['jour'] = date('l', strtotime($validated['date']));

            // Suppression des clés qui ne sont pas dans le modèle
            unset($data['heure_debut'], $data['heure_fin'], $data['capacite']);

            \Log::info('Données préparées pour création:', $data);

            $cours = Cours::create($data);
            \Log::info('Cours créé:', ['cours' => $cours]);

            return response()->json([
                'success' => true,
                'message' => 'Cours créé avec succès',
                'cours' => $cours
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du cours:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du cours: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cours $cours)
    {
        $cours->load(['coach', 'inscriptions']);

        if (request()->ajax()) {
            return response()->json($cours);
        }

        return view('cours.show', compact('cours'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cours $cours)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'coach_id' => 'required|exists:users,id',
                'capacite' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'type_cours' => 'required|string',
                'duree_minutes' => 'required|integer|min:30|max:180',
                'niveau' => 'required|string',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',
                'salle_id' => 'required|exists:salles,id',
                'date' => 'required|date'
            ]);

            // Préparation des données pour la mise à jour
            $data = $validated;
            $data['horaire_debut'] = $validated['heure_debut'];
            $data['horaire_fin'] = $validated['heure_fin'];
            $data['capacite_max'] = $validated['capacite'];
            $data['jour'] = $this->convertirJourEnFrancais(date('l', strtotime($validated['date'])));

            // Suppression des clés qui ne sont pas dans le modèle
            unset($data['heure_debut'], $data['heure_fin'], $data['capacite']);

            $cours->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Cours modifié avec succès',
                'cours' => $cours
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du cours: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

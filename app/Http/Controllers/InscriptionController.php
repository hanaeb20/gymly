<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Salle $salle)
    {
        return view('salles.inscription', compact('salle'));
    }

    public function store(Request $request, Salle $salle)
    {
        try {
            // Vérifier si l'utilisateur est déjà inscrit dans une salle
            if (Auth::user()->salle_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous êtes déjà inscrit dans une salle.'
                ], 400);
            }

            // Vérifier si l'utilisateur a déjà une inscription en attente pour cette salle
            $existingInscription = Inscription::where('user_id', Auth::id())
                ->where('salle_id', $salle->id)
                ->where('statut', 'en_attente')
                ->first();

            if ($existingInscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà une demande d\'inscription en attente pour cette salle.'
                ], 400);
            }

            // Vérifier si l'email est déjà utilisé dans une autre inscription
            $existingEmail = Inscription::where('email', Auth::user()->email)
                ->where('id', '!=', $existingInscription ? $existingInscription->id : null)
                ->first();

            if ($existingEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette adresse email est déjà utilisée pour une autre inscription.'
                ], 400);
            }

            // Vérifier si la salle a des abonnements
            if (!$salle->abonnements || $salle->abonnements->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette salle n\'a pas d\'abonnements disponibles.'
                ], 400);
            }

            // Créer l'inscription avec les données de l'utilisateur connecté
            $inscription = Inscription::create([
                'user_id' => Auth::id(),
                'salle_id' => $salle->id,
                'abonnement_id' => $salle->abonnements->first()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'telephone' => Auth::user()->telephone ?? '',
                'date_naissance' => now(),
                'antecedents_medicaux' => '',
                'allergies' => '',
                'statut' => 'en_attente'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre demande d\'inscription a été envoyée avec succès.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur inscription: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription : ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Salle $salle)
    {
        try {
            // Vérifier si l'utilisateur est inscrit dans cette salle
            if (Auth::user()->salle_id !== $salle->id) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas inscrit dans cette salle.');
            }

            // Supprimer l'inscription
            $inscription = Inscription::where('user_id', Auth::id())
                ->where('salle_id', $salle->id)
                ->first();

            if ($inscription) {
                $inscription->delete();
            }

            // Mettre à jour l'utilisateur
            Auth::user()->update(['salle_id' => null]);

            return redirect()->back()->with('success', 'Votre inscription a été annulée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur annulation inscription: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'annulation de l\'inscription.');
        }
    }
}

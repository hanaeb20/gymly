<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur CoachController
 *
 * Ce contrôleur gère toutes les actions liées aux coachs dans l'application.
 * Il permet la gestion des coachs, leurs cours et leurs interactions avec les clients.
 *
 * Fonctionnalités principales :
 * - Gestion du profil des coachs
 * - Gestion des cours (création, modification, suppression)
 * - Gestion des présences et réservations
 * - Tableau de bord du coach
 */
class CoachController extends Controller
{
    /**
     * Constructeur du contrôleur
     * Applique les middlewares d'authentification et de rôle coach
     * aux méthodes spécifiques de l'espace coach
     */
    public function __construct()
    {
        // Appliquer le middleware uniquement aux méthodes de l'espace coach
        $this->middleware(['auth', 'coach'])->only([
            'dashboard',
            'showCours',
            'updatePresence',
            'updateReservationStatus',
            'cours',
            'createCours',
            'storeCours',
            'editCours',
            'updateCours',
            'destroyCours'
        ]);
    }

    /**
     * Affiche la liste des coachs
     * Accessible à tous les utilisateurs
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $coachs = User::where('role', 'coach')->get();
        return view('coachs.index', compact('coachs'));
    }

    /**
     * Affiche le profil détaillé d'un coach
     * Inclut ses cours et sa salle associée
     *
     * @param User $coach Le coach dont on veut voir le profil
     * @return \Illuminate\View\View
     */
    public function show(User $coach)
    {
        if ($coach->role !== 'coach') {
            abort(404);
        }
        $coach->load(['cours', 'salle']);
        return view('coachs.show', compact('coach'));
    }

    /**
     * Affiche le tableau de bord du coach connecté
     * Contient un résumé de ses activités et cours
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $coach = Auth::user();
        return view('coach.dashboard', compact('coach'));
    }

    /**
     * Affiche les détails d'un cours spécifique
     * Inclut la liste des participants
     *
     * @param int $id L'identifiant du cours
     * @return \Illuminate\View\View
     */
    public function showCours($id)
    {
        $cours = Cours::where('coach_id', Auth::id())
                     ->findOrFail($id);
        $participants = $cours->participants;
        return view('coach.cours-details', compact('cours', 'participants'));
    }

    /**
     * Met à jour le statut de présence d'un participant
     *
     * @param Request $request La requête HTTP
     * @param int $cours_id L'identifiant du cours
     * @param int $participant_id L'identifiant du participant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePresence(Request $request, $cours_id, $participant_id)
    {
        $cours = Cours::where('coach_id', Auth::id())->findOrFail($cours_id);
        $participant = $cours->participants()->findOrFail($participant_id);
        $participant->pivot->present = $request->present;
        $participant->pivot->save();
        return back()->with('success', 'Présence mise à jour');
    }

    /**
     * Affiche la liste des réservations pour les cours du coach
     */
    public function reservations()
    {
        $user = Auth::user();

        if ($user->role === 'coach') {
            $reservations = Reservation::select('reservations.*')
                ->join('cours', 'reservations.cours_id', '=', 'cours.id')
                ->join('users', 'reservations.client_id', '=', 'users.id')
                ->where('cours.coach_id', $user->id)
                ->whereNotNull('users.id')
                ->with(['cours', 'client', 'cours.coach'])
                ->get();
        } else {
            $reservations = Reservation::select('reservations.*')
                ->join('users', 'reservations.client_id', '=', 'users.id')
                ->where('reservations.client_id', $user->id)
                ->whereNotNull('users.id')
                ->with(['cours', 'cours.coach', 'client'])
                ->get();
        }

        // Vérification des données
        $validReservations = $reservations->filter(function($reservation) {
            return $reservation->client !== null && $reservation->cours !== null;
        });

        return view('coach.reservations', ['reservations' => $validReservations]);
    }

    /**
     * Met à jour le statut d'une réservation
     */
    public function updateReservationStatus(Request $request, $reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);
        $cours = $reservation->cours;

        if ($cours->coach_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $reservation->status = $request->status;
        $reservation->save();

        return back()->with('success', 'Statut de la réservation mis à jour');
    }

    /**
     * Affiche la liste des cours du coach
     */
    public function cours()
    {
        $coach = Auth::user();
        $cours = Cours::where('coach_id', $coach->id)->get();
        return view('coach.cours', compact('cours'));
    }

    /**
     * Affiche le formulaire de création d'un cours
     */
    public function createCours()
    {
        return view('coach.cours.create');
    }

    /**
     * Enregistre un nouveau cours
     */
    public function storeCours(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'places_disponibles' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);

        $cours = new Cours($request->all());
        $cours->coach_id = Auth::id();
        $cours->save();

        return redirect()->route('coach.cours')->with('success', 'Cours créé avec succès');
    }

    /**
     * Affiche le formulaire d'édition d'un cours
     */
    public function editCours($id)
    {
        $cours = Cours::where('coach_id', Auth::id())->findOrFail($id);
        return view('coach.cours.edit', compact('cours'));
    }

    /**
     * Met à jour un cours existant
     */
    public function updateCours(Request $request, $id)
    {
        $cours = Cours::where('coach_id', Auth::id())->findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'places_disponibles' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);

        $cours->update($request->all());

        return redirect()->route('coach.cours')->with('success', 'Cours mis à jour avec succès');
    }

    /**
     * Supprime un cours
     */
    public function destroyCours($id)
    {
        $cours = Cours::where('coach_id', Auth::id())->findOrFail($id);
        $cours->delete();

        return redirect()->route('coach.cours')->with('success', 'Cours supprimé avec succès');
    }
}

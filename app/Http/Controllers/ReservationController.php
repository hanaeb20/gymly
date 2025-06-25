<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('client_id', Auth::user()->id)->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|exists:cours,id',
                'date' => 'required|date',
            ]);

            $reservation = Reservation::create([
                'client_id' => Auth::user()->id,
                'cours_id' => $validated['cours_id'],
                'date_reservation' => $validated['date'],
                'statut' => 'en_attente',
                'montant' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation effectuée avec succès',
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->client_id !== Auth::user()->id) {
            abort(403);
        }

        $reservation->delete();
        return back()->with('success', 'Réservation annulée avec succès');
    }

    public function show(Reservation $reservation)
    {
        if (request()->ajax()) {
            return response()->json($reservation);
        }
        return view('reservations.show', compact('reservation'));
    }

    public function updateStatut(Request $request, Reservation $reservation)
    {
        try {
            $validated = $request->validate([
                'statut' => 'required|in:en_attente,confirmée,annulée',
                'commentaire' => 'nullable|string'
            ]);

            $reservation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Statut de la réservation mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

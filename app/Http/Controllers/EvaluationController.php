<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::where('user_id', auth()->id())->get();
        return view('evaluations.index', compact('evaluations'));
    }

    public function create(Coach $coach = null)
    {
        $message = session('error') ?? session('success') ?? null;
        $coaches = User::where('role', 'coach')->get();

        if ($coach) {
            $evolutionStats = $this->calculateEvolutionStats($coach);
            return view('evaluations.create', compact('coach', 'message', 'evolutionStats', 'coaches'));
        }

        return view('evaluations.create', compact('coaches', 'message'));
    }

    private function calculateEvolutionStats(Coach $coach)
    {
        $evaluations = $coach->evaluations()
            ->orderBy('created_at')
            ->get();

        $monthlyNotes = [];
        foreach ($evaluations as $evaluation) {
            $month = $evaluation->created_at->format('Y-m');
            if (!isset($monthlyNotes[$month])) {
                $monthlyNotes[$month] = [
                    'total' => 0,
                    'sum' => 0
                ];
            }
            $monthlyNotes[$month]['total']++;
            $monthlyNotes[$month]['sum'] += $evaluation->note;
        }

        $stats = [];
        foreach ($monthlyNotes as $month => $data) {
            $stats[$month] = [
                'moyenne' => $data['total'] > 0 ? $data['sum'] / $data['total'] : 0,
                'total' => $data['total']
            ];
        }

        return $stats;
    }

    public function store(Request $request, Coach $coach)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|min:10'
        ]);

        try {
            $evaluation = new Evaluation([
                'note' => $request->note,
                'commentaire' => $request->commentaire,
                'client_id' => auth()->id(),
                'coach_id' => $coach->id()
            ]);

            $evaluation->save();

            return redirect()->route('coaches.show', $coach)
                ->with('success', 'Votre évaluation a été enregistrée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de l\'évaluation: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre évaluation.');
        }
    }

    public function showCoachEvaluations(User $coach)
    {
        if ($coach->role !== 'coach') {
            abort(404);
        }

        $evaluations = Evaluation::where('coach_id', $coach->id)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('evaluations.coach', compact('coach', 'evaluations'));
    }
}

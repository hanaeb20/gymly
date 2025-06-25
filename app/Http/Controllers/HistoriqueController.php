<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
    public function cours()
    {
        $cours = Cours::whereHas('participants', function($query) {
            $query->where('user_id', auth()->id());
        })->where('date', '<', now())->get();

        return view('historique.cours', compact('cours'));
    }

    public function evaluations()
    {
        $evaluations = Evaluation::where('user_id', auth()->id())->get();
        return view('historique.evaluations', compact('evaluations'));
    }
}

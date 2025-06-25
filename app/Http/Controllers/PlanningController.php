<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index()
    {
        $planning = Cours::whereHas('participants', function($query) {
            $query->where('user_id', auth()->id());
        })->orderBy('date')->get();

        return view('planning.index', compact('planning'));
    }
}

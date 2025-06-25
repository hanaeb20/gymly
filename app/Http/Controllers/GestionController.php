<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function horaires()
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        return redirect()->route('gerant.horaires');
    }

    public function equipements()
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        return redirect()->route('gerant.equipements');
    }

    public function abonnements()
    {
        if (Auth::user()->role !== 'gerant') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        return redirect()->route('gerant.abonnements');
    }
}

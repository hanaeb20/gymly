<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $salles = Salle::paginate(9);
        Log::info('Salles chargées:', ['count' => $salles->count()]);
        return view('home', compact('salles'));
    }
}

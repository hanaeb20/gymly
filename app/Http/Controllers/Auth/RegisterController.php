<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur RegisterController
 *
 * Ce contrôleur gère l'inscription des utilisateurs dans l'application.
 * Il gère trois types d'utilisateurs : clients, coachs et gérants.
 *
 * Fonctionnalités principales :
 * - Affichage du formulaire d'inscription
 * - Validation des données d'inscription
 * - Création des utilisateurs selon leur rôle
 * - Gestion des codes d'inscription pour les coachs
 */
class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $type = $request->query('type', 'client');

        switch ($type) {
            case 'coach':
                return view('auth.register-coach');
            case 'client':
                return view('auth.register-client');
            default:
                return view('auth.register-client');
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = $this->create($request->all());
            Auth::login($user);

            // Role-based redirection
            switch ($user->role) {
                case 'coach':
                    return redirect()->route('coach.dashboard');
                case 'gerant':
                    return redirect()->route('gerant.dashboard');
                case 'client':
                default:
                    return redirect()->route('salles.index');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function showProfileChoice()
    {
        if (Auth::check()) {
            return redirect()->route('salles.index');
        }
        return view('auth.choose-profile');
    }

    /**
     * Valide les données d'inscription selon le type d'utilisateur
     *
     * @param array $data Les données à valider
     * @return \Illuminate\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'string', 'in:client,coach,gerant'],
        ];

        // Règles spécifiques pour les clients et coachs
        if ($data['type'] === 'client' || $data['type'] === 'coach') {
            $rules['nom'] = ['required', 'string', 'max:255'];
            $rules['prenom'] = ['required', 'string', 'max:255'];
        }

        // Règles spécifiques pour les gérants
        if ($data['type'] === 'gerant') {
            $rules['nom_salle'] = ['required', 'string', 'max:255'];
            $rules['adresse'] = ['required', 'string', 'max:255'];
            $rules['telephone'] = ['required', 'string', 'max:20'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Crée un nouvel utilisateur dans la base de données
     *
     * @param array $data Les données validées
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $userData = [
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['type'],
        ];

        // Données spécifiques pour les clients et coachs
        if ($data['type'] === 'client' || $data['type'] === 'coach') {
            $userData['nom'] = $data['nom'];
            $userData['prenom'] = $data['prenom'];
            $userData['name'] = $data['nom'] . ' ' . $data['prenom'];
        }
        // Données spécifiques pour les gérants
        else if ($data['type'] === 'gerant') {
            $userData['nom_salle'] = $data['nom_salle'];
            $userData['adresse'] = $data['adresse'];
            $userData['telephone'] = $data['telephone'];
            $userData['name'] = $data['nom_salle'];
        }

        return User::create($userData);
    }
}

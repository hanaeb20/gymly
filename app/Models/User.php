<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modèle User - Gère les utilisateurs de l'application
 *
 * Ce modèle représente les utilisateurs du système avec trois rôles principaux :
 * - Gérant : Gère une ou plusieurs salles de sport
 * - Coach : Anime des cours dans une salle
 * - Client : Utilise les services de la salle
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',              // Nom d'utilisateur
        'email',             // Email (unique)
        'password',          // Mot de passe (hashé)
        'role',              // Rôle (gerant, coach, client)
        'nom',               // Nom de famille
        'prenom',            // Prénom
        'telephone',         // Numéro de téléphone
        'specialite',        // Spécialité (pour les coachs)
        'experience',        // Années d'expérience (pour les coachs)
        'salle_id',          // ID de la salle associée
        'inscription_code',  // Code d'inscription unique
        'adresse',           // Adresse postale
        'statut'            // Statut de l'utilisateur
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation.
     * Ces champs ne seront pas visibles dans les réponses JSON
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Méthodes d'accès aux identifiants
     */
    public function id()
    {
        return $this->getKey();
    }

    public function getKey()
    {
        return $this->attributes[$this->getKeyName()];
    }

    public function getKeyName()
    {
        return 'id';
    }

    /**
     * Relations pour les gérants
     * Un gérant peut avoir plusieurs salles et coachs
     */
    public function salles()
    {
        return $this->hasMany(Salle::class, 'gerant_id');
    }

    public function coachs()
    {
        return $this->hasMany(User::class, 'gerant_id')->where('role', 'coach');
    }

    /**
     * Relations pour les coachs
     * Un coach appartient à un gérant et peut avoir plusieurs cours
     */
    public function gerant()
    {
        return $this->belongsTo(User::class, 'gerant_id');
    }

    public function cours()
    {
        return $this->hasMany(Cours::class, 'coach_id');
    }

    /**
     * Relations pour les clients
     * Un client peut avoir plusieurs réservations, évaluations et inscriptions
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'client_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    /**
     * Méthodes de vérification des rôles
     * Permettent de vérifier facilement le rôle d'un utilisateur
     */
    public function isGerant()
    {
        return $this->role === 'gerant';
    }

    public function isCoach()
    {
        return $this->role === 'coach';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Relation avec la salle
     * Un utilisateur peut être associé à une salle
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}

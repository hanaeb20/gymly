<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Coach - Représente un coach dans l'application
 *
 * Ce modèle étend le modèle User pour gérer les fonctionnalités spécifiques aux coachs :
 * - Gestion des cours
 * - Suivi des évaluations
 * - Gestion des réservations
 *
 * Note : Les coachs sont stockés dans la table 'users' avec un rôle spécifique
 */
class Coach extends User
{
    /**
     * Spécifie la table utilisée pour ce modèle
     * Les coachs sont stockés dans la même table que les utilisateurs
     */
    protected $table = 'users';

    /**
     * Récupère l'identifiant du coach
     * Hérite de la méthode du modèle User
     */
    public function id()
    {
        return parent::id();
    }

    /**
     * Relation avec les évaluations
     * Un coach peut recevoir plusieurs évaluations de la part des clients
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'coach_id');
    }

    /**
     * Relation avec les réservations
     * Un coach peut avoir plusieurs réservations pour ses cours
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'coach_id');
    }

    /**
     * Relation avec les cours
     * Un coach peut animer plusieurs cours
     */
    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'coach_id');
    }
}

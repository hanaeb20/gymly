<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Abonnement - Gère les abonnements des clients aux salles
 *
 * Ce modèle représente les abonnements des clients aux salles de sport :
 * - Lien entre client et salle
 * - Gestion des périodes d'abonnement
 * - Types d'abonnement et tarifs
 * - Suivi du statut de l'abonnement
 */
class Abonnement extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     */
    protected $fillable = [
        'salle_id',      // ID de la salle concernée
        'user_id',       // ID du client abonné
        'type',          // Type d'abonnement (mensuel, annuel, etc.)
        'date_debut',    // Date de début de l'abonnement
        'date_fin',      // Date de fin de l'abonnement
        'prix',          // Prix de l'abonnement
        'statut'         // Statut de l'abonnement (actif, expiré, etc.)
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     */
    protected $casts = [
        'date_debut' => 'datetime',  // Conversion en objet DateTime
        'date_fin' => 'datetime',    // Conversion en objet DateTime
        'prix' => 'float'           // Conversion en nombre décimal
    ];

    /**
     * Relation avec la salle
     * Un abonnement est lié à une salle
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    /**
     * Relation avec l'utilisateur
     * Un abonnement appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Evaluation - Gère les évaluations des coachs par les clients
 *
 * Ce modèle représente les évaluations que les clients peuvent donner aux coachs :
 * - Système de notation
 * - Commentaires et feedback
 * - Visibilité des évaluations
 * - Lien entre client et coach
 */
class Evaluation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     */
    protected $fillable = [
        'client_id',     // ID du client qui évalue
        'coach_id',      // ID du coach évalué
        'note',          // Note attribuée (généralement sur 5 ou 10)
        'commentaire',   // Commentaire détaillé sur l'évaluation
        'est_visible'    // Indique si l'évaluation est visible publiquement
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     */
    protected $casts = [
        'est_visible' => 'boolean'  // Conversion en booléen
    ];

    /**
     * Relation avec le client
     * Une évaluation est faite par un client
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relation avec le coach
     * Une évaluation concerne un coach
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}

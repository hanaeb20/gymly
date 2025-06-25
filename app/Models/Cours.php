<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Cours - Représente un cours de sport dans l'application
 *
 * Ce modèle gère toutes les informations relatives à un cours :
 * - Informations de base (nom, description, type)
 * - Planning (date, horaires, durée)
 * - Capacité et prix
 * - Relations avec le coach et la salle
 * - Gestion des participants et réservations
 */
class Cours extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     */
    protected $fillable = [
        'nom',              // Nom du cours
        'description',      // Description détaillée
        'coach_id',        // ID du coach qui anime le cours
        'salle_id',        // ID de la salle où se déroule le cours
        'horaire_debut',   // Heure de début du cours
        'horaire_fin',     // Heure de fin du cours
        'capacite_max',    // Nombre maximum de participants
        'type_cours',      // Type de cours (yoga, fitness, etc.)
        'duree_minutes',   // Durée du cours en minutes
        'prix',            // Prix du cours
        'niveau',          // Niveau requis (débutant, intermédiaire, etc.)
        'date',            // Date du cours
        'jour'             // Jour de la semaine
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     */
    protected $casts = [
        'horaire_debut' => 'datetime',  // Conversion en objet DateTime
        'horaire_fin' => 'datetime',    // Conversion en objet DateTime
        'date' => 'date'               // Conversion en objet Date
    ];

    /**
     * Relation avec le coach
     * Un cours est animé par un coach
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Relation avec la salle
     * Un cours se déroule dans une salle
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    /**
     * Relation avec les participants
     * Un cours peut avoir plusieurs participants
     * La table pivot 'cours_user' stocke la présence
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'cours_user')
                    ->withPivot('present')  // Inclut le statut de présence
                    ->withTimestamps();     // Gère les timestamps de la relation
    }

    /**
     * Relation avec les réservations
     * Un cours peut avoir plusieurs réservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Relation avec les évaluations
     * Un cours peut recevoir plusieurs évaluations
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Relation avec les inscriptions
     * Un cours peut avoir plusieurs inscriptions
     */
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}

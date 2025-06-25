<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Inscription - Gère les inscriptions des clients aux salles
 *
 * Ce modèle représente les inscriptions des clients aux salles de sport :
 * - Informations personnelles du client
 * - Données médicales importantes
 * - Lien avec l'abonnement
 * - Statut de l'inscription
 */
class Inscription extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     */
    protected $fillable = [
        'user_id',              // ID de l'utilisateur inscrit
        'salle_id',             // ID de la salle d'inscription
        'abonnement_id',        // ID de l'abonnement associé
        'name',                 // Nom complet
        'email',                // Adresse email
        'telephone',            // Numéro de téléphone
        'date_naissance',       // Date de naissance
        'antecedents_medicaux', // Antécédents médicaux importants
        'allergies',            // Allergies connues
        'statut'               // Statut de l'inscription
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     */
    protected $casts = [
        'date_naissance' => 'date',           // Conversion en objet Date
        'antecedents_medicaux' => 'string',   // Conversion en chaîne de caractères
        'allergies' => 'string'              // Conversion en chaîne de caractères
    ];

    /**
     * Relation avec l'utilisateur
     * Une inscription appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la salle
     * Une inscription est liée à une salle
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    /**
     * Relation avec l'abonnement
     * Une inscription est associée à un abonnement
     */
    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Reservation - Gère les réservations de cours
 *
 * Ce modèle représente les réservations effectuées par les clients pour les cours :
 * - Lien entre client et cours
 * - Gestion des dates et statuts
 * - Suivi des paiements
 * - Commentaires et feedback
 */
class Reservation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     */
    protected $fillable = [
        'client_id',         // ID du client qui réserve
        'cours_id',          // ID du cours réservé
        'date_reservation',  // Date et heure de la réservation
        'statut',           // Statut de la réservation (confirmée, annulée, etc.)
        'montant',          // Montant payé pour la réservation
        'commentaire'       // Commentaire optionnel sur la réservation
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     */
    protected $casts = [
        'date_reservation' => 'datetime',  // Conversion en objet DateTime
        'montant' => 'decimal:2'          // Conversion en décimal avec 2 décimales
    ];

    /**
     * Relation avec le client
     * Une réservation appartient à un client
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relation avec le cours
     * Une réservation est liée à un cours
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}

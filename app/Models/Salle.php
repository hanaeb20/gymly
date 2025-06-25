<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Abonnement;
use App\Models\User;
use App\Models\Equipement;
use App\Models\Service;
use App\Models\Photo;
use App\Models\Horaire;
use App\Models\Cours;
use App\Models\Annonce;

/**
 * Modèle Salle - Représente une salle de sport dans l'application
 *
 * Ce modèle gère toutes les informations relatives à une salle de sport :
 * - Informations de base (nom, adresse, contact)
 * - Équipements et services proposés
 * - Horaires d'ouverture
 * - Photos et médias
 * - Gestion des abonnements
 * - Cours et annonces
 */
class Salle extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Ces champs peuvent être remplis via create() ou update()
     */
    protected $fillable = [
        'nom',               // Nom de la salle
        'description',       // Description détaillée
        'adresse',          // Adresse postale
        'ville',            // Ville
        'code_postal',      // Code postal
        'telephone',        // Numéro de téléphone
        'email',            // Adresse email
        'site_web',         // Site web
        'logo',             // Logo de la salle
        'photo_couverture', // Photo de couverture
        'horaires_ouverture', // Horaires d'ouverture
        'equipements',      // Liste des équipements
        'services',         // Liste des services
        'avis',            // Avis des clients
        'note',            // Note moyenne
        'statut',          // Statut de la salle
        'gerant_id',       // ID du gérant
        'capacite'         // Capacité d'accueil
    ];

    /**
     * Les attributs qui doivent être convertis.
     * Définit comment les champs doivent être traités lors de la conversion
     */
    protected $casts = [
        'horaires_ouverture' => 'array',  // Horaires stockés en JSON
        'equipements' => 'array',         // Équipements stockés en JSON
        'services' => 'array',            // Services stockés en JSON
        'avis' => 'array',               // Avis stockés en JSON
        'est_active' => 'boolean',       // Statut actif/inactif
        'prix_abonnement' => 'float'     // Prix en décimal
    ];

    /**
     * Relation avec le gérant
     * Une salle appartient à un gérant
     */
    public function gerant()
    {
        return $this->belongsTo(User::class, 'gerant_id');
    }

    /**
     * Relation avec les utilisateurs
     * Une salle peut avoir plusieurs utilisateurs (clients)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation avec les photos
     * Une salle peut avoir plusieurs photos
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Relation avec les horaires
     * Une salle peut avoir plusieurs horaires d'ouverture
     */
    public function horaires()
    {
        return $this->hasMany(Horaire::class);
    }

    /**
     * Relation avec les services
     * Une salle peut proposer plusieurs services
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * Relation avec les cours
     * Une salle peut proposer plusieurs cours
     */
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    /**
     * Relation avec les annonces
     * Une salle peut publier plusieurs annonces
     */
    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    /**
     * Relation avec les équipements
     * Une salle peut posséder plusieurs équipements
     */
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class);
    }

    /**
     * Relation avec les abonnements
     * Une salle peut avoir plusieurs abonnements
     */
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    /**
     * Accesseur pour les services
     * Convertit la chaîne JSON en tableau
     */
    public function getServicesAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Vérifie si un utilisateur est inscrit à cette salle
     *
     * @param int $userId ID de l'utilisateur à vérifier
     * @return bool True si l'utilisateur est inscrit
     */
    public function isInscrit($userId)
    {
        return $this->users()->where('id', $userId)->exists();
    }
}

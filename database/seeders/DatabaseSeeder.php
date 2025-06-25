<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Création de l'utilisateur de test
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        // Création du gérant
        $gerant = \App\Models\User::create([
            'name' => 'Gerant Test',
            'email' => 'gerant@test.com',
            'password' => Hash::make('password'),
            'role' => 'gerant',
            'email_verified_at' => now(),
            'siret' => '12345678901234',
            'nom_salle' => 'Fitness Plus',
            'salle_code' => 'FP123456'
        ]);

        // Création des salles
        $salles = [
            [
                'nom' => 'Fitness Plus',
                'description' => 'Une salle de sport moderne avec des équipements de dernière génération',
                'adresse' => '123 rue du Sport',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'telephone' => '01 23 45 67 89',
                'email' => 'contact@fitnessplus.fr',
                'horaires_ouverture' => json_encode(['Lundi-Vendredi: 7h-22h', 'Samedi: 8h-20h', 'Dimanche: 9h-18h']),
                'equipements' => json_encode(['Tapis de course', 'Vélos elliptiques', 'Machines de musculation', 'Zone cardio']),
                'prix_abonnement' => 49.99,
                'logo' => 'https://placehold.co/200x200/DE2A80/ffffff?text=FP',
                'photo_couverture' => 'https://placehold.co/1200x400/DE2A80/ffffff?text=Fitness+Plus',
                'est_active' => true,
                'gerant_id' => $gerant->id
            ],
            [
                'nom' => 'Sport Elite',
                'description' => 'Une salle premium pour les sportifs exigeants',
                'adresse' => '456 avenue du Fitness',
                'ville' => 'Lyon',
                'code_postal' => '69001',
                'telephone' => '04 56 78 90 12',
                'email' => 'contact@sportelite.fr',
                'horaires_ouverture' => json_encode(['Lundi-Vendredi: 6h-23h', 'Samedi: 7h-21h', 'Dimanche: 8h-20h']),
                'equipements' => json_encode(['Salle de crossfit', 'Piscine', 'Sauna', 'Salle de yoga']),
                'prix_abonnement' => 79.99,
                'logo' => 'https://placehold.co/200x200/DE2A80/ffffff?text=SE',
                'photo_couverture' => 'https://placehold.co/1200x400/DE2A80/ffffff?text=Sport+Elite',
                'est_active' => true,
                'gerant_id' => $gerant->id
            ]
        ];

        foreach ($salles as $salle) {
            $salleModel = \App\Models\Salle::create($salle);

            // Création des services pour chaque salle
            $services = [
                [
                    'nom' => 'Cours de Yoga',
                    'description' => 'Cours de yoga pour tous niveaux',
                    'public_cible' => 'Tous',
                    'prix' => 25.00,
                    'est_active' => true
                ],
                [
                    'nom' => 'CrossFit',
                    'description' => 'Entraînement fonctionnel intensif',
                    'public_cible' => 'Expert',
                    'prix' => 35.00,
                    'est_active' => true
                ],
                [
                    'nom' => 'Pilates',
                    'description' => 'Cours de pilates pour renforcer votre corps',
                    'public_cible' => 'Tous',
                    'prix' => 30.00,
                    'est_active' => true
                ]
            ];

            foreach ($services as $service) {
                $service['salle_id'] = $salleModel->id;
                \App\Models\Service::create($service);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Salle;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salles = [
            [
                'nom' => 'Gym Elite',
                'description' => 'Une salle de sport moderne et équipée',
                'adresse' => '123 Avenue Hassan II',
                'ville' => 'Casablanca',
                'code_postal' => '20000',
                'telephone' => '0612345678',
                'email' => 'contact@gymelite.com',
                'prix_abonnement' => 299.99,
                'gerant_id' => 1,
                'est_active' => true,
                'services' => json_encode(['Musculation', 'Cardio', 'Yoga']),
                'equipements' => json_encode(['Tapis de course', 'Vélos elliptiques', 'Poids libres'])
            ],
            [
                'nom' => 'Fitness Center',
                'description' => 'Centre de fitness complet',
                'adresse' => '45 Boulevard Anfa',
                'ville' => 'Casablanca',
                'code_postal' => '20000',
                'telephone' => '0623456789',
                'email' => 'contact@fitnesscenter.com',
                'prix_abonnement' => 249.99,
                'gerant_id' => 1,
                'est_active' => true,
                'services' => json_encode(['Fitness', 'Pilates', 'Zumba']),
                'equipements' => json_encode(['Machines de musculation', 'Salle de cardio', 'Studio de danse'])
            ]
        ];

        foreach ($salles as $salle) {
            Salle::create($salle);
        }
    }
}

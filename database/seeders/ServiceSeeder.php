<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['nom' => 'Musculation', 'description' => 'Salle de musculation complète'],
            ['nom' => 'Cardio', 'description' => 'Équipements cardio-vasculaires'],
            ['nom' => 'Yoga', 'description' => 'Cours de yoga'],
            ['nom' => 'Fitness', 'description' => 'Cours de fitness'],
            ['nom' => 'Pilates', 'description' => 'Cours de pilates'],
            ['nom' => 'Zumba', 'description' => 'Cours de zumba'],
            ['nom' => 'CrossFit', 'description' => 'Entraînement CrossFit'],
            ['nom' => 'Boxe', 'description' => 'Cours de boxe']
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

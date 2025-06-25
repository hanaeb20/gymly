/**
 * Configuration de Vite pour le projet
 * Vite est un outil de build moderne qui offre une expérience de développement rapide
 */

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        // Plugin Laravel pour l'intégration avec Vite
        laravel({
            // Fichiers d'entrée pour le build
            input: [
                'resources/css/app.css',  // Styles principaux
                'resources/js/app.js',    // JavaScript principal
            ],
            refresh: true,  // Active le rechargement automatique
        }),
        // Plugin Tailwind CSS pour le styling
        tailwindcss(),
    ],
});

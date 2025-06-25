<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Supprimer les anciennes colonnes si elles existent
            if (Schema::hasColumn('cours', 'heure_debut')) {
                $table->dropColumn('heure_debut');
            }
            if (Schema::hasColumn('cours', 'heure_fin')) {
                $table->dropColumn('heure_fin');
            }

            // S'assurer que les bonnes colonnes existent
            if (!Schema::hasColumn('cours', 'horaire_debut')) {
                $table->time('horaire_debut')->nullable();
            }
            if (!Schema::hasColumn('cours', 'horaire_fin')) {
                $table->time('horaire_fin')->nullable();
            }
            if (!Schema::hasColumn('cours', 'capacite_max')) {
                $table->integer('capacite_max')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Ne rien faire dans le down car nous ne voulons pas recréer les mauvaises colonnes
        });
    }
};

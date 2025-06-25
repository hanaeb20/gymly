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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('client');
            }
            if (!Schema::hasColumn('users', 'specialite')) {
                $table->string('specialite')->nullable();
            }
            if (!Schema::hasColumn('users', 'experience')) {
                $table->text('experience')->nullable();
            }
            if (!Schema::hasColumn('users', 'gym_code')) {
                $table->string('gym_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'nom_salle')) {
                $table->string('nom_salle')->nullable();
            }
            if (!Schema::hasColumn('users', 'siret')) {
                $table->string('siret')->nullable();
            }
            if (!Schema::hasColumn('users', 'salle_code')) {
                $table->string('salle_code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'specialite', 'experience', 'gym_code', 'nom_salle', 'siret', 'salle_code']);
        });
    }
};

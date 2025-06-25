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
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('telephone');
            $table->string('email');
            $table->json('horaires_ouverture');
            $table->json('equipements');
            $table->decimal('prix_abonnement', 8, 2);
            $table->string('logo')->nullable();
            $table->string('photo_couverture')->nullable();
            $table->foreignId('gerant_id')->constrained('users');
            $table->boolean('est_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};

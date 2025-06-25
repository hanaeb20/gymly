<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('type_cours');
            $table->integer('capacite_max')->default(10);
            $table->integer('duree_minutes')->default(60);
            $table->decimal('prix', 8, 2)->default(0);
            $table->string('niveau')->default('débutant');
            $table->json('jours_disponibles')->nullable();
            $table->time('heure_debut');
            $table->time('heure_fin')->nullable();
            $table->foreignId('salle_id')->constrained('salles');
            $table->foreignId('coach_id')->constrained('users');
            $table->boolean('est_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cours');
    }
};

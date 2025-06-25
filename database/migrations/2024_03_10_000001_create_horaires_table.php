<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->string('jour');
            $table->time('heure_ouverture');
            $table->time('heure_fermeture');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('horaires');
    }
};

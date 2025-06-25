<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salles', function (Blueprint $table) {
            if (!Schema::hasColumn('salles', 'capacite')) {
                $table->integer('capacite')->default(0)->after('description');
            }
            if (!Schema::hasColumn('salles', 'horaires_ouverture')) {
                $table->json('horaires_ouverture')->nullable()->after('photo_couverture');
            }
            if (!Schema::hasColumn('salles', 'equipements')) {
                $table->json('equipements')->nullable()->after('horaires_ouverture');
            }
            if (!Schema::hasColumn('salles', 'services')) {
                $table->json('services')->nullable()->after('equipements');
            }
            if (!Schema::hasColumn('salles', 'avis')) {
                $table->json('avis')->nullable()->after('services');
            }
            if (!Schema::hasColumn('salles', 'note')) {
                $table->float('note')->nullable()->after('avis');
            }
            if (!Schema::hasColumn('salles', 'statut')) {
                $table->string('statut')->nullable()->after('note');
            }
            if (!Schema::hasColumn('salles', 'est_active')) {
                $table->boolean('est_active')->default(true)->after('gerant_id');
            }
            if (!Schema::hasColumn('salles', 'prix_abonnement')) {
                $table->float('prix_abonnement')->nullable()->after('est_active');
            }
        });
    }

    public function down()
    {
        Schema::table('salles', function (Blueprint $table) {
            $table->dropColumn([
                'capacite',
                'horaires_ouverture',
                'equipements',
                'services',
                'avis',
                'note',
                'statut',
                'est_active',
                'prix_abonnement'
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->string('jour')->nullable()->after('date');
        });
    }

    public function down()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropColumn('jour');
        });
    }
};

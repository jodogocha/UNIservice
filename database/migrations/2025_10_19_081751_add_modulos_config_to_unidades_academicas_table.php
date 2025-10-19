<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unidades_academicas', function (Blueprint $table) {
            $table->json('modulos_activos')->nullable()->after('logo');
            $table->json('configuracion')->nullable()->after('modulos_activos');
        });
    }

    public function down(): void
    {
        Schema::table('unidades_academicas', function (Blueprint $table) {
            $table->dropColumn(['modulos_activos', 'configuracion']);
        });
    }
};
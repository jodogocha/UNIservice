<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido')->after('name');
            $table->string('documento')->unique()->nullable()->after('apellido');
            $table->string('telefono')->nullable()->after('documento');
            $table->foreignId('dependencia_id')->nullable()->constrained('dependencias')->onDelete('set null')->after('telefono');
            $table->foreignId('unidad_academica_id')->nullable()->constrained('unidades_academicas')->onDelete('set null')->after('dependencia_id');
            $table->boolean('activo')->default(true)->after('unidad_academica_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dependencia_id']);
            $table->dropForeign(['unidad_academica_id']);
            $table->dropColumn(['apellido', 'documento', 'telefono', 'dependencia_id', 'unidad_academica_id', 'activo']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código único del ticket (ej: TK-2025-0001)
            $table->foreignId('solicitante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dependencia_id')->constrained('dependencias')->onDelete('cascade');
            $table->foreignId('unidad_academica_id')->constrained('unidades_academicas')->onDelete('cascade');
            
            // Información del ticket
            $table->string('asunto');
            $table->text('descripcion');
            $table->enum('tipo_servicio', [
                'mantenimiento',
                'asesoramiento',
                'reparacion',
                'configuracion',
                'otro'
            ])->default('mantenimiento');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            
            // Estados del ticket
            $table->enum('estado', [
                'pendiente',
                'en_proceso',
                'listo',
                'finalizado',
                'cancelado'
            ])->default('pendiente');
            
            // Asignación y gestión
            $table->foreignId('asignado_a')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observaciones')->nullable();
            $table->text('solucion')->nullable();
            
            // Fechas importantes
            $table->timestamp('fecha_asignacion')->nullable();
            $table->timestamp('fecha_listo')->nullable();
            $table->timestamp('fecha_finalizado')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Para eliminación lógica
            
            // Índices para mejorar rendimiento
            $table->index('codigo');
            $table->index('estado');
            $table->index('solicitante_id');
            $table->index('asignado_a');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
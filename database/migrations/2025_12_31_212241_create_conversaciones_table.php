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
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->id();
            $table->string('estudiante_nombre');
            $table->string('estudiante_dni', 8);
            $table->text('mensaje_usuario');
            $table->text('respuesta_asistente');
            $table->timestamp('fecha_conversacion');
            $table->timestamps();
            
            $table->index('estudiante_dni');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversaciones');
    }
};

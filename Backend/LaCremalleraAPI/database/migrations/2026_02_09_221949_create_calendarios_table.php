<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendario', function (Blueprint $table) {
            $table->id('eventoId');
            $table->string('titulo', 100);
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedBigInteger('usuarioId');
            $table->unsignedBigInteger('empleadoId')->nullable();
            $table->unsignedBigInteger('trabajoId')->nullable();

            $table->foreign('usuarioId')->references('usuarioId')->on('usuarios');
            $table->foreign('empleadoId')->references('usuarioId')->on('usuarios');
            $table->foreign('trabajoId')->references('trabajoId')->on('trabajos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendario');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id('trabajoId');
            $table->unsignedBigInteger('prendaId');
            $table->unsignedBigInteger('empleadoId')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_entrega');
            $table->enum('estado', ['pendiente', 'en_proceso', 'listo', 'entregado'])->default('pendiente');
            $table->decimal('precio', 10, 2)->nullable();

            $table->foreign('prendaId')->references('prendaId')->on('prendas');
            $table->foreign('empleadoId')->references('usuarioId')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};

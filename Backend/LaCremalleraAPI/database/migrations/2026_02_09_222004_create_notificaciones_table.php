<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('notificacionId');
            $table->unsignedBigInteger('receptorId');
            $table->unsignedBigInteger('remitenteId');
            $table->unsignedBigInteger('trabajoId')->nullable();
            $table->enum('tipo', [
                'recordatorio_entrega',
                'trabajo_listo',
                'factura_generada',
                'notificacion'
            ]);
            $table->string('asunto', 100)->nullable();
            $table->text('mensaje');
            $table->timestamp('fecha_envio')->useCurrent();

            $table->foreign('receptorId')->references('usuarioId')->on('usuarios');
            $table->foreign('remitenteId')->references('usuarioId')->on('usuarios');
            $table->foreign('trabajoId')->references('trabajoId')->on('trabajos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};

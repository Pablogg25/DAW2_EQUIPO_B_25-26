<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('facturaId');
            $table->unsignedBigInteger('usuarioId');
            $table->date('fecha');
            $table->boolean('pagado')->default(false);
            $table->decimal('total_calculado', 10, 2)->nullable();

            $table->foreign('usuarioId')->references('usuarioId')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};

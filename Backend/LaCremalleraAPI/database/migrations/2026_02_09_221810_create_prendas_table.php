<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prendas', function (Blueprint $table) {
            $table->id('prendaId');
            $table->unsignedBigInteger('usuarioId');
            $table->string('tipo', 50);
            $table->text('descripcion')->nullable();
            $table->string('color', 30)->nullable();
            $table->string('talla', 10)->nullable();

            $table->foreign('usuarioId')->references('usuarioId')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prendas');
    }
};

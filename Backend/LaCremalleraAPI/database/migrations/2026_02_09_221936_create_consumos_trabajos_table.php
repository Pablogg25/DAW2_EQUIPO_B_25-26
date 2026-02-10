<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consumos_trabajo', function (Blueprint $table) {
            $table->unsignedBigInteger('trabajoId');
            $table->unsignedBigInteger('itemId');
            $table->integer('cantidad_usada');

            $table->primary(['trabajoId', 'itemId']);

            $table->foreign('trabajoId')->references('trabajoId')->on('trabajos');
            $table->foreign('itemId')->references('itemId')->on('inventario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumos_trabajo');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factura_trabajos', function (Blueprint $table) {
            $table->unsignedBigInteger('facturaId');
            $table->unsignedBigInteger('trabajoId');

            $table->primary(['facturaId', 'trabajoId']);

            $table->foreign('facturaId')->references('facturaId')->on('facturas');
            $table->foreign('trabajoId')->references('trabajoId')->on('trabajos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_trabajos');
    }
};

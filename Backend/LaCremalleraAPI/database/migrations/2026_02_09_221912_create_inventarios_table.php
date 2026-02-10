<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id('itemId');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('cantidad')->default(0);
            $table->integer('stock_minimo')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};

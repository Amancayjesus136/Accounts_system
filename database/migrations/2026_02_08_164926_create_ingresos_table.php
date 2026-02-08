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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id('id_ingreso');
            $table->text('descripcion');
            $table->decimal('monto', 10, 2);
            $table->bigInteger('id_usuario');
            $table->bigInteger('id_tarjeta');
            $table->bigInteger('id_categoria');
            $table->integer('estado_ingreso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};

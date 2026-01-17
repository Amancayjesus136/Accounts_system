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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->bigInteger('id_usuario');
            $table->bigInteger('id_plataforma');
            $table->bigInteger('id_visibilidad');
            $table->boolean('verificacion');
            $table->text('descripcion');
            $table->integer('estado_cuenta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};

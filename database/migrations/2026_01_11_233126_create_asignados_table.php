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
        Schema::create('asignados', function (Blueprint $table) {
            $table->id('id_asignado');
            $table->bigInteger('id_grupo');
            $table->bigInteger('id_usuario');
            $table->integer('estado_asignado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignados');
    }
};

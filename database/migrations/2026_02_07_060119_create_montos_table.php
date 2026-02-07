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
        Schema::create('montos', function (Blueprint $table) {
            $table->id('id_monto');
            $table->bigInteger('id_tarjeta');
            $table->decimal('monto_tarjeta', 8, 2);
            $table->integer('estado_monto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('montos');
    }
};

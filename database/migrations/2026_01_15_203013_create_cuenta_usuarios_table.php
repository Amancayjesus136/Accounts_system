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
        Schema::create('cuenta_usuarios', function (Blueprint $table) {
            $table->id('id_cuenta_usuario');
            $table->string('username_cuenta')->nullable();
            $table->string('number_cuenta')->nullable();
            $table->string('email_cuenta')->nullable();
            $table->string('password_cuenta');
            $table->bigInteger('id_cuenta');
            $table->bigInteger('estado_cuenta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_usuarios');
    }
};

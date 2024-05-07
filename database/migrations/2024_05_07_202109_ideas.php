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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('antecedente')->max(3000);
            //$table->string('condiciones_actuales');
            $table->string('propuesta')->max(3000);
            $table->unsignedBigInteger('estatus');
            $table->foreign('estatus')->references('id')->on('estado_ideas')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            table->foreign('user_id')->references('id')->on('usuarios')->onDelete('cascade');
            //$table->string('equipo_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

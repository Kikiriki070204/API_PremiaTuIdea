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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_idea');
            $table->foreign('id_idea')->references('id')->on('ideas');
            $table->unsignedBigInteger('responsable');
            $table->foreign('responsable')->references('id')->on('usuarios');
            $table->date('fecha_inicio');
            $table->date('fecha_finalizacion')->nullable();
            $table->unsignedBigInteger('id_estado_actividad')->default(1);
            $table->foreign('id_estado_actividad')->references('id')->on('estado_actividades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};

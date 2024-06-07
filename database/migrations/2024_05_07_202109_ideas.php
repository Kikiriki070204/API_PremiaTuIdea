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
            $table->string('propuesta')->max(3000);
            $table->unsignedBigInteger('estatus')->default(1);
            $table->foreign('estatus')->references('id')->on('estado_ideas')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->float('ahorro')->nullable()->default(0);
            $table->boolean('contable')->default(null)->nullable();
            $table->unsignedBigInteger('campos_id')->nullable();
            $table->foreign('campos_id')->references('id')->on('campos')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->integer('puntos')->default(0);
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

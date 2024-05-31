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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ibm')->unique();
            $table->string('nombre');
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('cascade');
            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->unsignedBigInteger('locacion_id')->nullable();
            $table->foreign('locacion_id')->references('id')->on('locaciones')->onDelete('cascade');
            $table->string('password')->nullable();
            $table->unsignedBigInteger('rol_id')->default(3);
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('puntos')->default(0);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
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

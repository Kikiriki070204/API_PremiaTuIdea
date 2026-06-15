<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrusel_imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('imagen');
            $table->string('mime_type');
            $table->integer('orden')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrusel_imagenes');
    }
};

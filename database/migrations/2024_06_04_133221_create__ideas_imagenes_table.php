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
        Schema::create('ideas_imagenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idea_id');
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('cascade');
            $table->string('imagen');
            $table->string('mime_type');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_ideas_imagenes');
    }
};

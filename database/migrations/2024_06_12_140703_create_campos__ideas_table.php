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
        Schema::create('campos__ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->references('id')->on('ideas')->onDelete('cascade');
            $table->foreignId('campo_id')->references('id')->on('campos')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campos__ideas');
    }
};

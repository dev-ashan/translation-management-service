<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tag_translation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->foreignId('translation_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['tag_id', 'translation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_translation');
    }
}; 
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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locale_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value');
            $table->string('group')->default('general');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['locale_id', 'key', 'group']);
            $table->index(['key', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};

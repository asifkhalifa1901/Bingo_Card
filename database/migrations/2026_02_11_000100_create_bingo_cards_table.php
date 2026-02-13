<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bingo_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('grid_size')->default(5);
            $table->json('cells');
            $table->boolean('is_template')->default(false);
            $table->string('thumbnail_url')->nullable();
            $table->string('category')->nullable();
            $table->string('source')->default('manual'); // manual | ai | template
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bingo_cards');
    }
};


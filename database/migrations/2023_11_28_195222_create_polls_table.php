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
        Schema::create('polls', function (Blueprint $table) {
            $table->uuid('id');
            $table->tinyInteger('poll_index')->nullable();
            $table->string('question');
            $table->json('options');

            $table->uuid('presentation_id');
            $table->foreign('presentation_id')->references('id')->on('presentations')->onDelete('cascade');

            $table->unique(['presentation_id', 'poll_index']);
            $table->index('question');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};

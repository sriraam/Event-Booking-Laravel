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
        Schema::create('category_event', function (Blueprint $table) {
            $table->foreignId('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreignId('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->primary(['event_id','category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_event');
    }
};

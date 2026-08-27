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
        Schema::create('seat_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_session_id')->constrained('event_sessions')->cascadeOnDelete();
            $table->foreignId('seat_master_id')->constrained('seat_masters')->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->nullable()->index(); // Belum dikaitkan FK karena order dibuat di Fase 5
            $table->enum('status', ['available', 'locked', 'sold'])->default('available');
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();

            $table->unique(['event_session_id', 'seat_master_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_availabilities');
    }
};

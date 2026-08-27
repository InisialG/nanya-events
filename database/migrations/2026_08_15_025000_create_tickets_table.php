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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique(); // TKT-20260815-XYZ1
            $table->string('qr_code_hash')->unique(); // Hash string for QR scanner validation
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('seat_availability_id')->constrained('seat_availabilities')->cascadeOnDelete();
            $table->enum('status', ['valid', 'used', 'cancelled'])->default('valid');
            $table->timestamp('scanned_at')->nullable();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

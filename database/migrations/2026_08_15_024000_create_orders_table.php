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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // NYA-20260815-XYZ8
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_session_id')->constrained('event_sessions')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->decimal('total_amount', 12, 2);
            $table->integer('unique_code')->default(0); // 3-digit kode unik (1..999)
            $table->decimal('final_amount', 12, 2);
            $table->enum('status', ['pending_payment', 'waiting_verification', 'paid', 'cancelled', 'rejected'])->default('pending_payment');
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

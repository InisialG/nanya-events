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
        Schema::create('seat_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->cascadeOnDelete();
            $table->foreignId('seat_category_id')->nullable()->constrained('seat_categories')->nullOnDelete();
            $table->string('seat_code'); // Kode unik: "A-1", "B-12"
            $table->integer('row_num'); // Index baris (1=A, 2=B, dst)
            $table->integer('col_num'); // Index kolom (1, 2, 3...)
            $table->boolean('is_active')->default(true); // false jika tiang / gang (aisle)
            $table->timestamps();

            $table->unique(['venue_id', 'seat_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_masters');
    }
};

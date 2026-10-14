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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. FOODIEHEMAT, DISKON20
            $table->string('name'); // e.g. "Diskon Pembukaan Foodie"
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed'); // fixed (Rp) or percentage (%)
            $table->decimal('discount_value', 12, 2); // e.g. 15000 or 20 (for 20%)
            $table->decimal('min_spend', 12, 2)->default(0); // Minimal subtotal belanja
            $table->decimal('max_discount', 12, 2)->nullable(); // Maksimal potongan diskon jika percentage
            $table->integer('quota')->default(100); // Kuota pemakaian
            $table->integer('used_count')->default(0); // Jumlah kali terpakai
            $table->date('valid_until')->nullable(); // Masa berlaku voucher
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

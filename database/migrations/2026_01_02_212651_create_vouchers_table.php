<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['discount_percentage', 'discount_fixed', 'cashback']);
            $table->decimal('value', 12, 2); // Nilai diskon/cashback
            $table->decimal('min_purchase', 12, 2)->default(0); // Minimal pembelian
            $table->decimal('max_discount', 12, 2)->nullable(); // Max potongan (untuk persentase)
            $table->integer('usage_limit')->nullable(); // Batas penggunaan total
            $table->integer('usage_count')->default(0); // Sudah digunakan berapa kali
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
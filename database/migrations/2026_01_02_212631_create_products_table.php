<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('specifications')->nullable(); // JSON untuk spesifikasi
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->string('main_image');
            $table->json('gallery_images')->nullable(); // Multiple images
            $table->enum('status', ['available', 'out_of_stock', 'discontinued'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
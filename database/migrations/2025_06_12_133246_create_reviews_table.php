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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // FK
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('product_id'); 
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        
            // Isi review
            $table->float('rating');      // 1 - 5
            $table->text('review');
            $table->string('color')->nullable();  // warna varian, opsional
            $table->string('image')->nullable();  // path atau URL gambar review
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

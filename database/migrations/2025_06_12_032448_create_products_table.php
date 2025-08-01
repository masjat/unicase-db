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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price',10,2);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->float('rating')->default(0); 
            $table->string('color')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('category_id'); // FK
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

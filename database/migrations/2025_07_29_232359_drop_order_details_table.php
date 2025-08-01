<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('order_details');
    }

    public function down(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_detail_id');
            $table->unsignedBigInteger('id_checkout');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('custom_case_id')->nullable();
            $table->string('variant', 50);
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('id_checkout')->references('id')->on('checkouts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('custom_case_id')->references('id')->on('custom_cases')->onDelete('set null');
        });
    }
};

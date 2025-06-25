<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('id_method');
            $table->dateTime('payment_date')->nullable();
            $table->decimal('total_payment', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('id_method')->references('id')->on('method')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_checkout');
            $table->unsignedBigInteger('id_method');
            $table->dateTime('payment_date')->nullable();
            $table->decimal('total_payment', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_checkout')->references('id')->on('checkouts')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

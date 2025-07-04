<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('checkout_id');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('order_id')->unique();
            $table->string('payment_type'); // virtual_account, gopay, dll
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('checkout_id')->references('id')->on('checkouts')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};

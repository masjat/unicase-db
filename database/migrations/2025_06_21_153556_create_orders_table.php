<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id');
            $table->unsignedBigInteger('id_shipping');
            $table->decimal('service_fee', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('id_status');
            $table->timestamps();

            // Foreign Keys (optional, aktifkan jika tabel terkait tersedia)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('id_shipping')->references('id')->on('shippings')->onDelete('cascade');
            $table->foreign('id_status')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

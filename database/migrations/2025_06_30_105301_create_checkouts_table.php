<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();

            // Manual foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_address_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('shipping_option_id');

            $table->decimal('total_product_price', 10, 2);
            $table->decimal('total_shipping_cost', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('application_fee', 10, 2)->default(0);
            $table->decimal('total_purchase', 10, 2);

            $table->string('status')->default('pending');
            $table->timestamps();

            // Foreign constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
            $table->foreign('shipping_option_id')->references('id')->on('shipping_options')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};

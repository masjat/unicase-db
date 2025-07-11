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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('receivers_name');
            $table->string('phone_number');

            $table->unsignedInteger('province_id'); // dari RajaOngkir
            $table->string('province_name');
            $table->unsignedInteger('city_id');  // dari RajaOngkir
            $table->string('city');

            $table->string('postal_code');
            $table->text('full_address');
            $table->text('note_to_courier')->nullable();

            $table->boolean('is_primary')->default(false);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};

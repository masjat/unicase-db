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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: BCA, BRI, Mandiri
            $table->string('account_number'); // Nomor rekening
            $table->string('account_name');   // Nama pemilik rekening
            $table->string('bank_logo')->nullable(); // URL logo bank (optional)
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['transfer'])->default('transfer'); // Hanya transfer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

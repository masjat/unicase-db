<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('custom_cases', function (Blueprint $table) {
            $table->id('custom_case_id');
            $table->unsignedBigInteger('user_id');
            $table->text('image_url')->nullable();
            $table->enum('case_type', ['hardcase', 'softcase', 'premium anti-crack']);
            $table->enum('print_effect', ['glossy', 'doff', 'glow effect']);
            $table->string('brand', 80);
            $table->string('phone_model', 80);
            $table->text('description')->nullable();
            $table->decimal('price_case', 10, 2);
            $table->decimal('price_print', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            // Foreign Key jika user_id mengacu pada tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_cases');
    }
};


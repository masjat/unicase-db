<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_address_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
        
            $table->string('courier'); // jne, tiki, sicepat
            $table->string('courier_service'); // REG, YES, OKE, dll
            $table->integer('shipping_cost')->default(0);
        
            $table->decimal('subtotal', 12, 2); // total harga produk
            $table->decimal('total', 12, 2);    // subtotal + ongkir
        
            $table->string('status')->default('pending'); // pending, paid, delivered, etc
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};

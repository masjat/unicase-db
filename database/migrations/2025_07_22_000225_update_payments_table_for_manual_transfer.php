<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop kolom yang tidak digunakan untuk transfer manual
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropColumn(['payment_type', 'bank', 'va_number', 'expired_at']);

            // Ubah kolom payment_method_id agar tidak nullable kalau kamu ingin wajib
            // $table->unsignedBigInteger('payment_method_id')->nullable(false)->change();

            // Tambahkan kolom baru jika ada (contoh: bukti transfer)
            $table->string('transfer_proof')->nullable(); // URL bukti transfer (jika upload bukti)
            $table->text('note')->nullable();              // catatan dari user
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Restore struktur lama (jika dibutuhkan rollback)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('payment_type')->nullable();
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->dropColumn(['transfer_proof', 'note']);
        });
    }
};

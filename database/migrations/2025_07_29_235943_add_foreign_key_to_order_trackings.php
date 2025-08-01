<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_trackings', function (Blueprint $table) {
            // Tambah foreign key ke checkout_id
            $table->foreign('id_checkout')
                ->references('id')
                ->on('checkouts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('order_trackings', function (Blueprint $table) {
            // Drop foreign key kalau rollback
            $table->dropForeign(['id_checkout']);
        });
    }
};

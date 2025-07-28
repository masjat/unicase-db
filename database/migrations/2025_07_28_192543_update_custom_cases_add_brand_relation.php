<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('custom_cases', function (Blueprint $table) {
            // Tambah kolom relasi
            $table->unsignedBigInteger('brand_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('brand_type_id')->nullable()->after('brand_id');

            // Hapus kolom lama
            $table->dropColumn('brand');
            $table->dropColumn('phone_model');

            // Foreign key relasi ke tabel baru
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('brand_type_id')->references('id')->on('brand_types')->onDelete('set null');

            // Optional: ubah format enum biar seragam
            $table->enum('case_type', ['hardcase', 'softcase', 'premium_anti_crack'])->change();
            $table->enum('print_effect', ['glossy', 'doff', 'glow_effect'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('custom_cases', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['brand_type_id']);
            $table->dropColumn(['brand_id', 'brand_type_id']);

            $table->string('brand', 80);
            $table->string('phone_model', 80);

            $table->enum('case_type', ['hardcase', 'softcase', 'premium anti-crack'])->change();
            $table->enum('print_effect', ['glossy', 'doff', 'glow effect'])->change();
        });
    }
};


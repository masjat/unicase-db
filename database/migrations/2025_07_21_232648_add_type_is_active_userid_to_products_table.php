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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type', ['ekslusif', 'custom'])->default('ekslusif')->after('id'); // posisinya bisa kamu sesuaikan
            $table->boolean('is_active')->default(true)->after('type');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_active']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

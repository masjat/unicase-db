<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->after('city_id');
            $table->string('district_name')->after('district_id');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->dropColumn(['district_id', 'district_name']);
        });
    }
};

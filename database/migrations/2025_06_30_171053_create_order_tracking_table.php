<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTrackingTable extends Migration
{
    public function up()
    {
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->id('tracking_id');
            $table->unsignedBigInteger('id_checkout');
            $table->string('status', 80);
            $table->timestamps();

            $table->foreign('id_checkout')->references('id')->on('checkouts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_tracking');
    }
}

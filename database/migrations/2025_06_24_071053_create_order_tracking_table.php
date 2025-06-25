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
            $table->unsignedBigInteger('order_id');
            $table->string('status', 80);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_tracking');
    }
}

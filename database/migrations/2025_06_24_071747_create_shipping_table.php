<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTable extends Migration
{
    public function up()
    {
        Schema::create('shipping', function (Blueprint $table) {
            $table->id('id_shipping');
            $table->string('type', 30);
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping');
    }
}

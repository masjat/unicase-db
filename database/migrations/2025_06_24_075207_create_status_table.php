<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTable extends Migration
{
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('status_name', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('status');
    }
}

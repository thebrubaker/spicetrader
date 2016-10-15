<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ship_id');
            $table->string('destination_type')->nullable();
            $table->integer('destination_id')->nullable();
            $table->integer('x');
            $table->integer('y');
            $table->date('depart_time');
            $table->date('arrival_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ship_schedules');
    }
}

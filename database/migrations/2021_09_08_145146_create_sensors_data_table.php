<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->date('date');
            $table->string('hour');
            $table->unsignedBigInteger('sensors_id');
            $table->foreign('sensors_id')->references('id')->on('sensors_data')->onDelete('cascade');
            $table->unsignedBigInteger('animal_id');
            $table->foreign('animal_id')->references('id')->on('animals')->onDelete('cascade');
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
        Schema::dropIfExists('sensors_data');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('region');
            $table->integer('salary'); 
            $table->unsignedBigInteger('fonction_id');
            $table->foreign('fonction_id')->references('id')->on('fonctions')->onDelete('cascade'); //create function module / id, name, farm_id
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); //create category hièrachique module / id, name h
            $table->string('supervisor');
           // $table->string('password');
            $table->unsignedBigInteger('contracttype_id');
            $table->foreign('contracttype_id')->references('id')->on('contracts')->onDelete('cascade'); // contracts type module / id, type 
            $table->unsignedBigInteger('farm_id');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
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
        Schema::dropIfExists('employees');
    }
}

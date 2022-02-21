<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierFarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_farm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            //$table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('farm_id');
           // $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
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
        Schema::dropIfExists('supplier_farm');
    }
}

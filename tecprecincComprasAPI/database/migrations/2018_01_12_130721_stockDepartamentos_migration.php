<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockDepartamentosMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockDepartamentos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('stock_id')->unsigned();
            $table->foreign('stock_id')->references('id')->on('stock');

            $table->integer('stock'); //existencias
            $table->integer('stock_min')->nullable(); //cantidad minima permitida

            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('departamentos');

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
        Schema::drop('stockDepartamentos');
    }
}

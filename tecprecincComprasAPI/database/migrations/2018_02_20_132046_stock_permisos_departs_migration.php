<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockPermisosDepartsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_permisos_departs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('stock_id')->unsigned(); // producto en el stock
            $table->foreign('stock_id')->references('id')->on('stock');

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
        Schema::drop('stock_permisos_departs');
    }
}

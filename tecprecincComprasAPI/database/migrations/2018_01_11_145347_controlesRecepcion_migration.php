<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ControlesRecepcionMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controlesRecepcion', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('compra_id')->unsigned();
            $table->foreign('compra_id')->references('id')->on('compras');

            $table->date('f_recepcion')->nullable(); //fecha de recepcion de la compra
            $table->text('documento')->nullable(); //url para documento adjunto factura o remito
            $table->text('nota_credito')->nullable(); //en caso de compra incompleta, definir bien*
            $table->string('estado'); //definir estados*

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
        Schema::drop('controlesRecepcion');
    }
}

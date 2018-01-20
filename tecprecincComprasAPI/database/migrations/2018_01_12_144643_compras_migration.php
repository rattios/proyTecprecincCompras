<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ComprasMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('presupuesto_id')->unsigned()->nullable();
            $table->foreign('presupuesto_id')->references('id')->on('presupuestos');

            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->integer('proveedor_id')->unsigned();
            $table->foreign('proveedor_id')->references('id')->on('proveedores');

            $table->string('estado'); //definir estados*
            $table->date('f_envio')->nullable(); //fecha de envio al proveedor
            $table->string('confir_ajuste')->nullable(); // definir este campo*
            $table->date('f_respuesta')->nullable(); //fecha de respuesta del proveedor
            $table->string('confir_rec_oc')->nullable(); // definir este campo*

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
        Schema::drop('compras');
    }
}

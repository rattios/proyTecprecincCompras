<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PresupuestosMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->integer('proveedor_id')->unsigned();
            $table->foreign('proveedor_id')->references('id')->on('proveedores');

            $table->string('estado'); //definir estados*
            $table->date('f_envio')->nullable(); //fecha de envio al proveedor
            $table->date('f_respuesta')->nullable(); //fecha de respuesta del proveedor
            $table->date('f_entrega')->nullable(); //fecha de la posible entrega por parte del proveedor
            $table->text('documento')->nullable(); //url para documento adjunto

            $table->timestamps(); //fecha de generacion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('presupuestos');
    }
}

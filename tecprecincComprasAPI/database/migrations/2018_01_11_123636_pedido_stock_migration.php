<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PedidoStockMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_stock', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            $table->integer('stock_id')->unsigned(); // producto solicitado
            $table->foreign('stock_id')->references('id')->on('stock');

            $table->integer('cantidad'); //Unidades de producto requerido
            $table->integer('aprobado')->nullable();
            $table->integer('entregado')->nullable();
            $table->date('f_entrega')->nullable();
            $table->integer('tipo_entrega')->nullable(); //1=picking, 2=transferencia, 3=compra
            $table->integer('devuelto')->nullable();
            $table->integer('cancelado')->nullable();
            $table->integer('pendiente')->nullable();
            $table->text('observaciones')->nullable();

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
        Schema::drop('pedido_stock');
    }
}

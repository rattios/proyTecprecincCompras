<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProveedoresMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razon_social')->unique();
            $table->string('nombre_fantacia')->unique()->nullable();
            $table->string('cuit')->unique();
            $table->string('telefono')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('habilitado'); // SI - NO 
            $table->string('estado'); // Definir estados*
            $table->integer('calificacion')->nullable();
            $table->text('motivo');
            $table->string('direccion');
            $table->string('forma_pago');
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
        Schema::drop('proveedores');
    }
}

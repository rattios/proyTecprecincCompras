<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsuariosMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono')->nullable();
            $table->integer('rol'); //1=cliente interno, 2=departamento de compras, 3=supervisores y gerentes
            $table->string('codigo_verificacion')->nullable();

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
        Schema::drop('usuarios');
    }
}

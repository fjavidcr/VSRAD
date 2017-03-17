<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('apellidos')->nullable();
            $table->string('direccion_fisica')->nullable();
            $table->string('telefono')->nullable();
            $table->string('fecha_registro')->nullable();
            $table->integer('id_tecnico')->nullable();
            $table->integer('id_comercial')->nullable();
            $table->integer('rol')->nullable();
            $table->float('oferta')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

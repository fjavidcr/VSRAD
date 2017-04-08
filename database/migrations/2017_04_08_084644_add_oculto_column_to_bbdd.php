<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOcultoColumnToBbdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->integer('oculto')->default(0);
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->integer('oculto')->default(0);
        });

        Schema::table('planos', function (Blueprint $table) {
            $table->integer('oculto')->default(0);
        });

        Schema::table('mensajes', function (Blueprint $table) {
            $table->integer('oculto')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('oculto')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('oculto');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('oculto');
        });

        Schema::table('planos', function (Blueprint $table) {
            $table->dropColumn('oculto');
        });

        Schema::table('mensajes', function (Blueprint $table) {
            $table->dropColumn('oculto');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('oculto');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProyectosDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('coste');
        });

        Schema::table('proyectos', function (Blueprint $table) {
            $table->integer('coste')->default(0);
        });


        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('id_tecnico');
        });

        Schema::table('proyectos', function (Blueprint $table) {
            $table->integer('id_tecnico')->nullable();
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
            $table->dropColumn('coste');
        });

        Schema::table('proyectos', function (Blueprint $table) {
            $table->integer('coste');
        });

        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('id_tecnico');
        });

        Schema::table('proyectos', function (Blueprint $table) {
            $table->integer('id_tecnico');
        });
    }
}
